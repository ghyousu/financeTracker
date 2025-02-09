-- auto update total balance after each transaction
CREATE OR REPLACE FUNCTION uft.auto_update_balance() RETURNS TRIGGER
AS $$
   DECLARE
      old_balance NUMERIC(10,2) := 0;
      new_balance NUMERIC(10,2) := 0;
   BEGIN
      SELECT total_balance INTO old_balance FROM uft.bank WHERE bank_db_id = NEW.bank_db_id;
      new_balance := old_balance + NEW.amount;

      UPDATE uft.bank SET total_balance = new_balance WHERE bank_db_id = NEW.bank_db_id;
      RETURN NEW;
   END;
$$ LANGUAGE 'plpgsql';

CREATE OR REPLACE TRIGGER auto_update_total_balance_trigger
   AFTER INSERT OR UPDATE ON uft.transaction
      FOR EACH ROW EXECUTE FUNCTION uft.auto_update_balance();

