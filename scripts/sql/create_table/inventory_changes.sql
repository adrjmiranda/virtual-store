CREATE TABLE IF NOT EXISTS inventory_changes (
  id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  product_id BIGINT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
  variant_id BIGINT NULL REFERENCES product_variants(id) ON DELETE CASCADE,
  change_quantity INTEGER NOT NULL,
  reason VARCHAR(255) NULL,
  created_by BIGINT NULL REFERENCES users(id),
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

CREATE TRIGGER update_inventory_changes_updated_at
BEFORE UPDATE ON inventory_changes
FOR EACH ROW
EXECUTE FUNCTION update_timestamp();