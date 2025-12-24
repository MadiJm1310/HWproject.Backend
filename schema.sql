
-- Drop tables if they exist (safe re-import)
DROP TABLE IF EXISTS experience_emergency;
DROP TABLE IF EXISTS driving_experience;
DROP TABLE IF EXISTS emergency_type;
DROP TABLE IF EXISTS parking_type;
DROP TABLE IF EXISTS road_condition;
DROP TABLE IF EXISTS weather;

-- WEATHER
CREATE TABLE weather (
    id INT AUTO_INCREMENT PRIMARY KEY,
    weather VARCHAR(50) NOT NULL
);

INSERT INTO weather (weather) VALUES
('Sunny'),
('Cloudy'),
('Rainy'),
('Snowy'),
('Windy');

-- ROAD CONDITION
CREATE TABLE road_condition (
    id INT AUTO_INCREMENT PRIMARY KEY,
    road_condition VARCHAR(50) NOT NULL
);

INSERT INTO road_condition (road_condition) VALUES
('Dry'),
('Wet'),
('Icy'),
('Mud');

-- PARKING TYPE / MANOEUVRE
CREATE TABLE parking_type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    method VARCHAR(100) NOT NULL
);

INSERT INTO parking_type (method) VALUES
('Parallel Parking'),
('Angle Parking'),
('Perpendicular Parking'),
('Roundabout');

-- EMERGENCY TYPE
CREATE TABLE emergency_type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(100) NOT NULL
);

INSERT INTO emergency_type (type) VALUES
('Flat Tire'),
('Brake Failure'),
('Engine Overheating'),
('Accident Avoidance'),
('Animal on the Road'),
('Steering Failure');

-- DRIVING EXPERIENCE
CREATE TABLE driving_experience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    distance_km DECIMAL(6,2) NOT NULL,
    weather_id INT NOT NULL,
    road_condition_id INT NOT NULL,
    parking_type_id INT NOT NULL,

    CONSTRAINT fk_weather
        FOREIGN KEY (weather_id)
        REFERENCES weather(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_road_condition
        FOREIGN KEY (road_condition_id)
        REFERENCES road_condition(id)
        ON DELETE RESTRICT,

    CONSTRAINT fk_parking_type
        FOREIGN KEY (parking_type_id)
        REFERENCES parking_type(id)
        ON DELETE RESTRICT
);

-- JUNCTION TABLE (MANY-TO-MANY)
CREATE TABLE experience_emergency (
    experience_id INT NOT NULL,
    emergency_id INT NOT NULL,
    PRIMARY KEY (experience_id, emergency_id),

    CONSTRAINT fk_experience
        FOREIGN KEY (experience_id)
        REFERENCES driving_experience(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_emergency
        FOREIGN KEY (emergency_id)
        REFERENCES emergency_type(id)
        ON DELETE CASCADE
);
