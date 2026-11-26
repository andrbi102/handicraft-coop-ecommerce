-- 001_create_tables.sql — اسکیما کامل فروشگاه تعاونی
-- SQLite-compatible (پیش‌فرض)؛ برای MySQL نوع‌ها به VARCHAR/INT适配 شوند.

PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS users (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    name            TEXT NOT NULL,
    email           TEXT NOT NULL UNIQUE,
    phone           TEXT NOT NULL DEFAULT '',
    password_hash   TEXT NOT NULL,
    role            TEXT NOT NULL DEFAULT 'customer'
                    CHECK (role IN ('customer','manager','accountant','admin')),
    status          TEXT NOT NULL DEFAULT 'active'
                    CHECK (status IN ('active','disabled')),
    created_at      TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS members (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    full_name       TEXT NOT NULL,
    craft           TEXT NOT NULL DEFAULT '',
    region          TEXT NOT NULL DEFAULT '',
    joined_at       TEXT NOT NULL,
    share_capital   INTEGER NOT NULL DEFAULT 0,
    phone           TEXT NOT NULL DEFAULT '',
    bio             TEXT NOT NULL DEFAULT '',
    status          TEXT NOT NULL DEFAULT 'active'
                    CHECK (status IN ('active','inactive')),
    created_at      TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS categories (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    name            TEXT NOT NULL,
    slug            TEXT NOT NULL UNIQUE,
    description     TEXT NOT NULL DEFAULT '',
    parent_id       INTEGER,
    sort_order      INTEGER NOT NULL DEFAULT 0,
    created_at      TEXT NOT NULL,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);
CREATE INDEX IF NOT EXISTS idx_categories_parent ON categories(parent_id);

CREATE TABLE IF NOT EXISTS products (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id     INTEGER NOT NULL,
    member_id       INTEGER,
    name            TEXT NOT NULL,
    slug            TEXT NOT NULL UNIQUE,
    description     TEXT NOT NULL DEFAULT '',
    price           INTEGER NOT NULL DEFAULT 0,
    cost            INTEGER NOT NULL DEFAULT 0,
    stock           INTEGER NOT NULL DEFAULT 0,
    sku             TEXT NOT NULL DEFAULT '',
    weight_grams    INTEGER NOT NULL DEFAULT 0,
    material        TEXT NOT NULL DEFAULT '',
    dimensions      TEXT NOT NULL DEFAULT '',
    image           TEXT NOT NULL DEFAULT '',
    is_featured     INTEGER NOT NULL DEFAULT 0,
    status          TEXT NOT NULL DEFAULT 'active'
                    CHECK (status IN ('active','draft','archived')),
    created_at      TEXT NOT NULL,
    updated_at      TEXT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);
CREATE INDEX IF NOT EXISTS idx_products_category ON products(category_id);
CREATE INDEX IF NOT EXISTS idx_products_member ON products(member_id);
CREATE INDEX IF NOT EXISTS idx_products_status ON products(status);

CREATE TABLE IF NOT EXISTS orders (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id         INTEGER NOT NULL,
    order_number    TEXT NOT NULL UNIQUE,
    status          TEXT NOT NULL DEFAULT 'pending'
                    CHECK (status IN ('pending','paid','processing','shipped','delivered','cancelled','refunded')),
    subtotal        INTEGER NOT NULL DEFAULT 0,
    discount        INTEGER NOT NULL DEFAULT 0,
    shipping_cost   INTEGER NOT NULL DEFAULT 0,
    total           INTEGER NOT NULL DEFAULT 0,
    shipping_name   TEXT NOT NULL DEFAULT '',
    shipping_phone  TEXT NOT NULL DEFAULT '',
    shipping_address TEXT NOT NULL DEFAULT '',
    shipping_city   TEXT NOT NULL DEFAULT '',
    shipping_postal_code TEXT NOT NULL DEFAULT '',
    notes           TEXT NOT NULL DEFAULT '',
    coupon_code     TEXT,
    created_at      TEXT NOT NULL,
    updated_at      TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
);
CREATE INDEX IF NOT EXISTS idx_orders_user ON orders(user_id);
CREATE INDEX IF NOT EXISTS idx_orders_status ON orders(status);

CREATE TABLE IF NOT EXISTS order_items (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id        INTEGER NOT NULL,
    product_id      INTEGER NOT NULL,
    product_name    TEXT NOT NULL,
    member_id       INTEGER,
    price           INTEGER NOT NULL DEFAULT 0,
    cost            INTEGER NOT NULL DEFAULT 0,
    quantity        INTEGER NOT NULL DEFAULT 1,
    subtotal        INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);
CREATE INDEX IF NOT EXISTS idx_order_items_order ON order_items(order_id);
CREATE INDEX IF NOT EXISTS idx_order_items_member ON order_items(member_id);

CREATE TABLE IF NOT EXISTS coupons (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    code            TEXT NOT NULL UNIQUE,
    type            TEXT NOT NULL DEFAULT 'fixed'
                    CHECK (type IN ('percent','fixed')),
    value           INTEGER NOT NULL DEFAULT 0,
    min_subtotal    INTEGER NOT NULL DEFAULT 0,
    usage_limit     INTEGER,
    used_count      INTEGER NOT NULL DEFAULT 0,
    expires_at      TEXT,
    status          TEXT NOT NULL DEFAULT 'active'
                    CHECK (status IN ('active','inactive')),
    created_at      TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS settlements (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id       INTEGER NOT NULL,
    order_item_id   INTEGER,
    order_id        INTEGER,
    amount          INTEGER NOT NULL DEFAULT 0,
    type            TEXT NOT NULL DEFAULT 'craft_wage'
                    CHECK (type IN ('craft_wage','profit_share','material','advance')),
    status          TEXT NOT NULL DEFAULT 'pending'
                    CHECK (status IN ('pending','paid')),
    period          TEXT NOT NULL,
    note            TEXT NOT NULL DEFAULT '',
    created_at      TEXT NOT NULL,
    settled_at      TEXT,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE RESTRICT,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
);
CREATE INDEX IF NOT EXISTS idx_settlements_member ON settlements(member_id);
CREATE INDEX IF NOT EXISTS idx_settlements_status ON settlements(status);

CREATE TABLE IF NOT EXISTS contact_messages (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    name            TEXT NOT NULL,
    email           TEXT NOT NULL,
    subject         TEXT NOT NULL DEFAULT '',
    message         TEXT NOT NULL,
    is_read         INTEGER NOT NULL DEFAULT 0,
    created_at      TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS settings (
    key             TEXT PRIMARY KEY,
    value           TEXT NOT NULL DEFAULT ''
);
