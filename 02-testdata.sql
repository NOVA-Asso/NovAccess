USE novaccess;

-- Insert categories used by test links. ON DUPLICATE KEY keeps it idempotent.
INSERT INTO categories (name, description) VALUES
('General', 'Miscellaneous public links'),
('Internal', 'Internal / intranet resources (requires auth)'),
('Documentation', 'Documentation and guides'),
('Search Engine', 'Web search engines'),
('Email', 'Webmail services'),
('Video', 'Video platforms'),
('Code Hosting', 'Code hosting and collaboration'),
('Q&A', 'Question & Answer sites'),
('Encyclopedia', 'Reference encyclopedias')
ON DUPLICATE KEY UPDATE description = VALUES(description);

-- Insert a few test links. Use ON DUPLICATE KEY to make re-runs safe.
INSERT INTO links (url, name, description, needs_auth) VALUES
('https://example.com/public', 'example', 'Public example link', 0),
('https://intranet.local/portal', 'intra', 'Internal portal (auth simulated)', 1),
('https://docs.example.com', 'docs', 'Documentation site', 0),
('https://www.google.com', 'google', 'Google search', 0),
('https://mail.google.com', 'gmail', 'Gmail (login required)', 1),
('https://www.youtube.com', 'youtube', 'YouTube video platform', 0),
('https://github.com', 'github', 'GitHub code hosting', 0),
('https://stackoverflow.com', 'stackov', 'Stack Overflow Q&A (short name)', 0),
('https://www.bing.com', 'bing', 'Bing search', 0),
('https://en.wikipedia.org', 'wikipedia', 'Wikipedia encyclopedia', 0)
ON DUPLICATE KEY UPDATE url = VALUES(url), description = VALUES(description), needs_auth = VALUES(needs_auth);

-- Assign categories to the inserted links by matching category names.
-- These are safe to re-run since categories were inserted above and updates are idempotent.
UPDATE links SET category_id = (SELECT id FROM categories WHERE name = 'General' LIMIT 1) WHERE name = 'example';
UPDATE links SET category_id = (SELECT id FROM categories WHERE name = 'Internal' LIMIT 1) WHERE name = 'intra';
UPDATE links SET category_id = (SELECT id FROM categories WHERE name = 'Documentation' LIMIT 1) WHERE name = 'docs';
UPDATE links SET category_id = (SELECT id FROM categories WHERE name = 'Search Engine' LIMIT 1) WHERE name IN ('google','bing');
UPDATE links SET category_id = (SELECT id FROM categories WHERE name = 'Email' LIMIT 1) WHERE name = 'gmail';
UPDATE links SET category_id = (SELECT id FROM categories WHERE name = 'Video' LIMIT 1) WHERE name = 'youtube';
UPDATE links SET category_id = (SELECT id FROM categories WHERE name = 'Code Hosting' LIMIT 1) WHERE name = 'github';
UPDATE links SET category_id = (SELECT id FROM categories WHERE name = 'Q&A' LIMIT 1) WHERE name = 'stackov';
UPDATE links SET category_id = (SELECT id FROM categories WHERE name = 'Encyclopedia' LIMIT 1) WHERE name = 'wikipedia';
