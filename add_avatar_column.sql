-- Add avatar column to users table
ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL AFTER email_verified_at;