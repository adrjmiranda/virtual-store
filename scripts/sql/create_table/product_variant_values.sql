CREATE TABLE IF NOT EXISTS product_variant_values (
  variant_id BIGINT NOT NULL REFERENCES product_variants(id) ON DELETE CASCADE,
  option_id BIGINT NOT NULL REFERENCES product_options(id) ON DELETE CASCADE,
  value_id BIGINT NOT NULL REFERENCES product_option_values(id) ON DELETE CASCADE,
  PRIMARY KEY (variant_id, option_id)
);