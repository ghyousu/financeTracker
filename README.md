# yoU Finance Tracker (UFT)

A small, self-hosted personal finance tracker. Track bank account balances and
individual transactions across multiple account owners from a single web page.

- **Stack:** plain PHP (no framework, no Composer) + PostgreSQL
- **Rendering:** server-side PHP includes producing classic HTML, plus vanilla
  JS/CSS
- **Database access:** raw `pg_*` calls with hand-built SQL strings

## Features

- **Balance summary** — bank accounts grouped by owner, each showing alias plus
  last 4 digits of the account number, with joint accounts styled differently.
  A grand total across every account is shown at the bottom.
- **Add transactions** — a grid with one row per bank account (amount, date,
  notes) so several transactions can be recorded in a single submission.
  Positive amounts are deposits, negative amounts are withdrawals.
- **Recent transactions** — newest first, with the amount used to color deposits
  vs. withdrawals.
- **Filters and preferences** — filter by bank, filter by notes substring
  (`ILIKE`), filter by an amount range, and set the max number of rows to
  display. Amounts may be positive or negative, and either end of the range may
  be left blank for an open-ended range. These are persisted **per user** in the
  database, not just in the session.
- **Multi-delete** — select any number of transaction rows and delete them; the
  bank balances are updated automatically by database triggers.

## Project layout

```
.
├── index.php          # Main page; assembles the page from forms/ fragments
├── login.php          # Login form (currently bypassed by auto-login)
├── logout.php         # Destroys the session
├── common.php         # All shared logic: DB connection, queries, row models
├── forms/             # HTML/PHP view fragments included by index.php
│   ├── top_panel.php             # Greeting + log out link
│   ├── showMainSummary.php       # Per-owner balances + grand total
│   ├── addNewTransactions.php    # New transaction entry grid
│   ├── showRecentTransactions.php # Filterable, deletable transaction list
│   └── editTransaction.php       # Stub, not wired up
├── post/              # Form action handlers; each mutates then redirects
│   ├── postNewTransactions.php
│   ├── deleteTransactions.php
│   ├── updateNumTransactions.php
│   ├── updateTransactionNotesFilter.php
│   ├── updateAmountFilter.php
│   └── applyBankNameFilter.php   # Triggered by the bank dropdown (GET)
├── schemas/           # PostgreSQL DDL, triggers, and seed data
│   ├── schema_setup.sql
│   ├── procedures.sql
│   └── test_data.sql
├── scripts/           # index.js, showRecentTransactions.js
├── style/             # One CSS file per view
└── run.bash           # Local run helper
```

## Request flow

1. A page (`index.php`) calls helpers in `common.php` to read data.
2. `common.php` opens a connection, runs a query, and maps rows into
   `SQLUserInfo` / `SQLBank` / `SQLTransaction` objects, which are handed to the
   `forms/` fragments for rendering.
3. A form submits to a handler in `post/`.
4. The handler calls a `common.php` write helper, then issues
   `header("location: /index.php")` — the classic Post/Redirect/Get pattern, so
   a refresh never re-submits.
5. The write is applied, and the database triggers adjust `bank.total_balance`.

## Database

Everything lives in the `uft` schema. The schema name is centralized in
`common.php` via `getCommonSchemaName()` along with `getUsersTableName()`,
`getBankTableName()`, and `getTransTableName()`.

### `uft.uft_user`

| Column | Notes |
| --- | --- |
| `user_id` | `serial` primary key |
| `user_name` | unique login name |
| `fname`, `lname` | unique together; used for the greeting |
| `pw` | `sha1(password)` |
| `max_trans` | max rows in the recent-transactions list (default 30) |
| `bank_id_filter` | `0` means all banks |
| `notes_filter` | substring matched against transaction notes |
| `min_amount_filter` | `NUMERIC(10,2)`; `NULL` = no lower bound |
| `max_amount_filter` | `NUMERIC(10,2)`; `NULL` = no upper bound |

The last five columns are the persisted UI preferences described above. Note the
amount bounds use `NULL` rather than a sentinel like `0` or `''`, because both
`0` and a negative value are legitimate things to filter on — an empty box in the
UI means "no bound", and blanking both boxes clears the filter.

### `uft.bank`

One row per bank account.

| Column | Notes |
| --- | --- |
| `bank_db_id` | `serial` primary key |
| `owner_name` | groups accounts for the summary view; not a FK |
| `bank_name` | e.g. `Chase` |
| `bank_alias` | unique short name, used to label rows and to build form field names |
| `min_balance`, `statement_date` | bookkeeping fields; not currently used by the UI |
| `bank_routing_num`, `bank_account_num` | unique together |
| `is_joint_account` | drives the summary row styling |
| `display_order` | row order within an owner |
| `total_balance` | maintained by triggers, not by application code |

### `uft.transaction`

| Column | Notes |
| --- | --- |
| `trans_id` | `serial` primary key |
| `bank_db_id` | FK → `bank`, `ON DELETE CASCADE` |
| `user_id` | FK → `uft_user`, `ON DELETE CASCADE` |
| `amount` | `NUMERIC(10,2)`; positive = deposit, negative = withdrawal |
| `notes` | free text, shown in the list and matched by `notes_filter` |
| `trans_date` | `TIMESTAMPTZ`, defaults to `now()` |
| `last_update` | `TIMESTAMPTZ`, defaults to `now()` |

### Triggers (`schemas/procedures.sql`)

- `auto_update_total_balance_trigger` — `AFTER INSERT OR UPDATE`:
  `bank.total_balance += NEW.amount` and stamps `last_update`.
- `auto_update_total_balance_on_delete_trigger` — `AFTER DELETE`:
  `bank.total_balance -= OLD.amount`.

This is the main reason application code never writes `total_balance` itself —
insert a transaction, and the balance follows.

### Seed data

`schemas/test_data.sql` creates two users (`myou`, `xxs`, both with password
`1234`) and nine bank accounts split between them, plus sample transactions
covering direct deposits, bills, rent, and credit-card payments.

## Conventions worth knowing before editing

- **Table and field names use `snake_case`** in SQL and `camelCase` in PHP
  properties.
- **Query helpers** live in `common.php` and are named for what they do:
  `getBanksPerOwner`, `getRecentTransctions`, `addTransactions`,
  `deleteTransactions`, `applyBankIdFilter`, `updateNotesFilter`,
  `updateAmountFilter`, `updateMaxTransactions`. `authenticateUser` both
  validates and populates `$_SESSION['sql_user_info']`.
- **Field names in the add-transaction form are built from the bank alias** as
  `<alias>@amount`, `<alias>@date`, `<alias>@notes`. `postNewTransactions.php`
  loops over the banks and rebuilds those names to read the POST body. So if you
  change how those inputs are named, the handler must change with it.
- **Logging** goes through `printDebug($str, $debug)` and
  `printInfo($str, $enabled)`; both are silent unless explicitly enabled.
  `printError` always echoes.
- **Session key:** `$_SESSION['sql_user_info']` holds a `SQLUserInfo`. Filter
  changes update both the database row and the in-session object so the current
  page and the next request agree.

## Known gaps

These are visible in the current code and worth addressing before this is
exposed to anything but localhost:

- **SQL injection.** Every query in `common.php` is built by string
  concatenation of user input — login credentials, the notes filter, POSTed
  amounts, and notes. The only escaping anywhere is a manual
  `str_replace("'", "''")` on the notes field in `postNewTransactions.php`. Use
  `pg_query_params()` instead. The amount range filter is the one exception: its
  bounds go through `normalizeAmountBound()`, which rejects anything
  `is_numeric()` won't take, so it does not add a new hole.
- **Authentication is bypassed.** `index.php` logs in as `myou` with a
  hardcoded password when no session exists, and `login.php` immediately
  redirects to the main page, so the real credential check is commented out.
  Passwords are also stored as unsalted `sha1`.
- **No authorization.** Any authenticated user sees all banks and can delete any
  transaction; `user_id` is recorded on insert but never used to scope queries.
- **`transaction` is a reserved SQL word.** The table name is unquoted
  throughout `schema_setup.sql` and `procedures.sql` and will need quoting, or
  renaming, to work.
- **Debug output is user-reachable.** `fetchQueryResults()` `die()`s with the
  full query string and the PostgreSQL error text.
- **Transaction editing is not implemented.** `forms/editTransaction.php` is an
  empty form and its `post/` target does not exist.
- **Fragile query building.** `getRecentTransctions()` appends a second
  `WHERE` clause after an `ORDER BY`/`LIMIT` when called with a non-zero
  `bank_db_id`; every current caller passes `0`, so the path is untested.
