-- auto update total balance after add/update transaction
CREATE OR REPLACE FUNCTION pbt.auto_update_balance() RETURNS TRIGGER
AS $$
   DECLARE
      old_balance NUMERIC(10,2) := 0;
      new_balance NUMERIC(10,2) := 0;
   BEGIN
      SELECT total_balance INTO old_balance FROM pbt.bank WHERE bank_db_id = NEW.bank_db_id;
      new_balance := old_balance + NEW.amount;
      NEW.last_update = now();

      UPDATE pbt.bank SET total_balance = new_balance WHERE bank_db_id = NEW.bank_db_id;
      RETURN NEW;
   END;
$$ LANGUAGE 'plpgsql';

-- auto update total balance after each DELETE
CREATE OR REPLACE FUNCTION pbt.auto_update_balance_on_delete() RETURNS TRIGGER
AS $$
   DECLARE
      old_balance NUMERIC(10,2) := 0;
      new_balance NUMERIC(10,2) := 0;
   BEGIN
      SELECT total_balance INTO old_balance FROM pbt.bank WHERE bank_db_id = OLD.bank_db_id;
      new_balance := old_balance - OLD.amount;

      UPDATE pbt.bank SET total_balance = new_balance WHERE bank_db_id = OLD.bank_db_id;
      RETURN OLD;
   END;
$$ LANGUAGE 'plpgsql';


CREATE OR REPLACE TRIGGER auto_update_total_balance_trigger
   AFTER INSERT OR UPDATE ON pbt.transaction
      FOR EACH ROW EXECUTE FUNCTION pbt.auto_update_balance();

CREATE OR REPLACE TRIGGER auto_update_total_balance_on_delete_trigger
   AFTER DELETE ON pbt.transaction
      FOR EACH ROW EXECUTE FUNCTION pbt.auto_update_balance_on_delete();

