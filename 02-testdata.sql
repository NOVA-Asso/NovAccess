USE novaccess;

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
