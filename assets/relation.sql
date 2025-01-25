CREATE TABLE artists (
    artist_id INT PRIMARY KEY,
    artistname VARCHAR(255) NOT NULL,
);

CREATE TABLE artworks (
    artwork_id INT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
);

CREATE TABLE artist_favorites (
    artist_id INT,
    artwork_id INT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (artist_id, artwork_id),
    FOREIGN KEY (artist_id) REFERENCES artists(artist_id),
    FOREIGN KEY (artwork_id) REFERENCES artworks(artwork_id)
);
