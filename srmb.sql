CREATE TABLE IF NOT EXISTS student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL,
    Name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    staff_id VARCHAR(20) NOT NULL,
    Name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS driver (
    id INT AUTO_INCREMENT PRIMARY KEY,
    driver_id VARCHAR(20) NOT NULL,
    Name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_number VARCHAR(20) NOT NULL,
    capacity INT NOT NULL,
    driver_id INT NOT NULL,
    destination VARCHAR(20) NOT NULL,
    FOREIGN KEY (driver_id) REFERENCES driver(id)
);

CREATE TABLE IF NOT EXISTS bus_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id_student INT,
    user_id_staff INT,
    bus_id INT NOT NULL,
    registration_date DATE NOT NULL,
    FOREIGN KEY (user_id_student) REFERENCES student(id),
    FOREIGN KEY (user_id_staff) REFERENCES staff(id),
    FOREIGN KEY (bus_id) REFERENCES buses(id)
);

CREATE TABLE IF NOT EXISTS LocationData (
    id INT AUTO_INCREMENT PRIMARY KEY,
    latitude DECIMAL(10, 6) NOT NULL,
    longitude DECIMAL(10, 6) NOT NULL,
    accuracy DECIMAL(10, 2) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    driver_id INT NOT NULL,
    FOREIGN KEY (driver_id) REFERENCES driver(id)
);

INSERT INTO student (student_id, name, password, email) VALUES
('shuvam1', 'shuvam', 'studentshuvam1', 'shuvam1student@gmail.com'),
('anurag1', 'anurag', 'studentanurag1', 'anurag1student@gmail.com'),
('divakar1', 'divakar', 'studentdivakar1', 'divakar1student@gmail.com'),
('milan1', 'milan', 'studentmilan1', 'milan1_student@gmail.com'),
('bishika1', 'bishika', 'studentbishika1', 'bishika1student@gmail.com'),
('dikshya1', 'dikshya', 'studentdikshya1', 'dikshya1student@gmail.com'),
('aayush1', 'aayush', 'studentaayush1', 'aayush1student@gmail.com'),
('samip1', 'samip', 'studentsamip1', 'samip1student@gmail.com'),
('avinash1', 'avinash', 'studentavinash1', 'avinash1student@gmail.com'),
('rahul1', 'rahul', 'studentrahul1', 'rahul1student@gmail.com');

INSERT INTO staff (staff_id, name, password, email) VALUES
('krishna1', 'krishna', 'staffkrishna1', 'krishna1staff@gmail.com'),
('aditya1', 'aditya', 'staffaditya1', 'aditya1staff@gmail.com'),
('arjun1', 'arjun', 'staffarjun1', 'arjun1staff@gmail.com'),
('karan1', 'karan', 'staffkaran1', 'karan1staff@gmail.com'),
('arya1', 'arya', 'staffarya1', 'arya1staff@gmail.com'),
('shilpa1', 'shilpa', 'staffshilpa1', 'shilpa1staff@gmail.com'),
('sweta1', 'sweta', 'staffsweta1', 'sweta1staff@gmail.com'),
('swathi1', 'swathi', 'staffswathi1', 'swathi1staff@gmail.com'),
('anika1', 'anika', 'staffanika1', 'anika1staff@gmail.com'),
('aryan1', 'aryan', 'staffaryan1', 'aryan1staff@gmail.com');

INSERT INTO driver (driver_id, name, password, email) VALUES
('ashish1', 'ashish', 'driverashish1', 'ashish1driver@gmail.com'),
('binod1', 'binod', 'driverbinod1', 'binod1driver@gmail.com'),
('dipesh1', 'dipesh', 'driverdipesh1', 'dipesh1driver@gmail.com'),
('santosh1', 'santosh', 'driversantosh1', 'santosh1driver@gmail.com'),
('harit1', 'harit', 'driverharit1', 'harit1driver@gmail.com'),
('pradeep1', 'pradeep', 'driverpradeep1', 'pradeep1driver@gmail.com'),
('naresh1', 'naresh', 'drivernaresh1', 'naresh1driver@gmail.com'),
('jayendra1', 'jayendra', 'driverjayendra1', 'jayendra1driver@gmail.com'),
('brijesh1', 'brijesh', 'driverbrijesh1', 'brijesh1driver@gmail.com'),
('alok1', 'alok', 'driveralok1', 'alok1driver@gmail.com');

INSERT INTO buses (bus_number, capacity, driver_id, destination) VALUES
('Bus1', 35, 1, 'Vijawada'),
('Bus2', 35, 2, 'Guntur'),
('Bus3', 35, 3, 'Manglagiri'),
('Bus4', 35, 4, 'Amaravati'),
('Bus5', 35, 5, 'Gannavaram'),
('Bus6', 35, 6, 'Gosala'),
('Bus7', 35, 7, 'Guntupalli'),
('Bus8', 35, 8, 'Raintree'),
('Bus9', 35, 9, 'Tadepalle'),
('Bus10', 35, 10, 'Benz Circle');

INSERT INTO LocationData (latitude, longitude, accuracy, driver_id) VALUES
(51.5074, 0.1278, 5.0, 1),
(40.7128, -74.0060, 7.5, 2),
(34.0522, -118.2437, 6.0, 3),
(37.7749, -122.4194, 7.0, 4),
(51.5074, 0.1278, 5.0, 5),
(51.5074, 0.1278, 5.0, 6),
(40.7128, -74.0060, 7.5, 7),
(34.0522, -118.2437, 6.0, 8),
(37.7749, -122.4194, 7.0, 9),
(48.8566, 2.3522, 6.5, 10);