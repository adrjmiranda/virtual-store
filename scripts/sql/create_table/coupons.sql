CREATE TYPE coupon_type AS ENUM ('percent', 'fixed');

CREATE TABLE IF NOT EXISTS coupons (
  id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  code VARCHAR(50) NOT NULL UNIQUE,
  type coupon_type NOT NULL,
  value INTEGER NOT NULL CHECK (value >= 0),
  usage_limit INTEGER NULL CHECK (usage_limit >= 0),
  used_count INTEGER NOT NULL DEFAULT 0,
  starts_at TIMESTAMP NOT NULL DEFAULT now(),
  expires_at TIMESTAMP NULL,
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

CREATE TRIGGER update_coupons_updated_at
BEFORE UPDATE ON coupons
FOR EACH ROW
EXECUTE FUNCTION update_timestamp();