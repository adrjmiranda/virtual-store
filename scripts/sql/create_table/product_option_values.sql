CREATE TABLE IF NOT EXISTS product_option_values (
  id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  option_id BIGINT NOT NULL REFERENCES product_options(id) ON DELETE CASCADE,
  value VARCHAR(100) NOT NULL,
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

CREATE TRIGGER update_product_option_values_updated_at
BEFORE UPDATE ON product_option_values
FOR EACH ROW
EXECUTE FUNCTION update_timestamp();