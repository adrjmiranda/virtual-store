CREATE TABLE IF NOT EXISTS order_discounts (
  id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  order_id BIGINT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
  discount_code VARCHAR(50) NOT NULL,
  discount_amount INTEGER NOT NULL CHECK (discount_amount >= 0),
  created_at TIMESTAMP NOT NULL DEFAULT now(),
  updated_at TIMESTAMP NOT NULL DEFAULT now()
);

CREATE OR REPLACE FUNCTION update_timestamp()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = now();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_order_discounts_updated_at
BEFORE UPDATE ON order_discounts
FOR EACH ROW
EXECUTE FUNCTION update_timestamp();