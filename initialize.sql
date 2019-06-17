CREATE TABLE Location (
	locationID char(30) PRIMARY KEY,
	buildingCode char(30),
	areaCode char(30),
	capacity int,
	bookable varchar2(1)
);

CREATE TABLE Office (
  officeNumber char(30) PRIMARY KEY,
	floorNumber int
);

CREATE TABLE PostalCode (
	postalCode char(30) PRIMARY KEY,
	province char(60),
	city char(60)
);

CREATE TABLE Visit (
	visitID char(30) PRIMARY KEY,
	accessDate date,
	timeIn timestamp,
	timeOut timestamp
);

CREATE TABLE Student (
	studentID char(30) PRIMARY KEY,
	name char(40),
	major char(60),
	address char(80),
	postalCode char(30),
	FOREIGN KEY (postalCode) REFERENCES PostalCode(postalCode) ON DELETE CASCADE
);

CREATE TABLE ResourceBasedAt (
  resourceName char(30) PRIMARY KEY,
 	description char(140),
	contact char(60) NOT NULL,
	hours char(50),
	locationID char(30),
	FOREIGN KEY (locationID) REFERENCES Location(locationID) ON DELETE CASCADE
);

CREATE TABLE Club (
  clubName char(30) PRIMARY KEY,
	description char(140),
	contact char(60) NOT NULL,
	officeNumber char(30),
	FOREIGN KEY (officeNumber) REFERENCES Office(officeNumber) ON DELETE CASCADE
);

CREATE TABLE ExecutiveOversees (
	executiveID char(30) PRIMARY KEY,
	position char(60) NOT NULL,
	seniorID char(30),
	FOREIGN KEY (executiveID) REFERENCES Student(studentID) ON DELETE CASCADE,
	FOREIGN KEY (seniorID) REFERENCES ExecutiveOversees(executiveID) ON DELETE CASCADE
);

CREATE TABLE MemberOf (
	clubName char(30),
	studentID char(30),
	PRIMARY KEY (clubName, studentID),
	FOREIGN KEY (clubName) REFERENCES Club(clubName) ON DELETE CASCADE,
	FOREIGN KEY (studentID) REFERENCES Student(studentID) ON DELETE CASCADE
);

CREATE TABLE Accesses (
	resourceName char(30),
	studentID char(30),
	visitID  char(30),
	PRIMARY KEY (resourceName, studentID, visitID),
	FOREIGN KEY (resourceName) REFERENCES ResourceBasedAt(resourceName) ON DELETE CASCADE,
	FOREIGN KEY (studentID) REFERENCES Student(studentID) ON DELETE CASCADE,
	FOREIGN KEY (visitID) REFERENCES Visit(visitID) ON DELETE CASCADE
);

CREATE TABLE BusinessLocatedAt (
	businessID char(30) PRIMARY KEY,
	name char(40),
	type char(60),
	description char(140),
	contact char(60),
	hours char(40),
	locationID char(30) NOT NULL,
	FOREIGN KEY (locationID) REFERENCES Location(locationID) ON DELETE CASCADE
);

CREATE TABLE EventHappensAtRunBy (
	eventName char(60),
	eventDate date,
	description char(140),
	tickets char(60),
	locationID char(30) NOT NULL,
	executiveID char(30) NOT NULL,
	PRIMARY KEY (eventName, eventDate),
	FOREIGN KEY (locationID) REFERENCES Location(locationID) ON DELETE CASCADE,
	FOREIGN KEY (executiveID) REFERENCES ExecutiveOversees(executiveID) ON DELETE CASCADE
);

CREATE TABLE BookingReserves (
	bookingID char(30) PRIMARY KEY,
	studentID char(30) NOT NULL,
	startDateTime date,
	endDateTime date,
	status char(140) NOT NULL,
	locationID char(30) NOT NULL,
	FOREIGN KEY (studentID) REFERENCES Student(studentID) ON DELETE CASCADE,
	FOREIGN KEY (locationID) REFERENCES Location(locationID) ON DELETE CASCADE
);

INSERT INTO Location
VALUES
	(
		'1',
		'SUB',
		'RM200',
		10,
		1
	);

INSERT INTO Location
VALUES
	(
		'2',
		'SUB',
		'RM2103',
		12,
		1
	);

INSERT INTO Location
VALUES
	(
		'3',
		'SUB',
		'RM2108',
		15,
		1
	);

INSERT INTO Location
VALUES
	(
		'4',
		'DMP',
		'RM301',
		200,
		1
	);

INSERT INTO Location
VALUES
	(
		'5',
		'SRC',
		'GYM1',
		40,
		1
	);

INSERT INTO Location
VALUES
	(
		'6',
		'SRC',
		'GYM2',
		40,
		1
	);

INSERT INTO Location
VALUES
	(
		'7',
		'SUB',
		'4TH FLOOR',
		100,
		1
	);

INSERT INTO Location
VALUES
	(
		'8',
		'SUB',
		'MAIN FLOOR',
		100,
		1
	);

INSERT INTO Location
VALUES
	(
		'9',
		'SUB',
		'LOWER LEVEL',
		100,
		1
	);

	INSERT INTO Office
	VALUES
	(
		'1',
		1
	);

	INSERT INTO Office
	VALUES
	(
		'2',
		2
	);

	INSERT INTO Office
	VALUES
	(
		'3',
		3
	);

	INSERT INTO Office
	VALUES
	(
		'4',
		4
	);

	INSERT INTO Office
	VALUES
	(
		'5',
		4
	);

	INSERT INTO PostalCode
	VALUES
	(
	  'V6T 1Z1',
	  'British Columbia',
	  'Vancouver'
	);

	INSERT INTO PostalCode
	VALUES
	(
	  'V6T 1Z2',
	  'British Columbia',
	  'Vancouver'
	);

	INSERT INTO PostalCode
	VALUES
	(
	  'V6T 1Z3',
	  'British Columbia',
	  'Vancouver'
	);

	INSERT INTO PostalCode
	VALUES
	(
	  'V6T 1Z4',
	  'British Columbia',
	  'Vancouver'
	);

	INSERT INTO PostalCode
	VALUES
	(
	  'V6T 1Z5',
	  'British Columbia',
	  'Vancouver'
	);

	INSERT INTO Visit
	VALUES
	(
	  '1',
	  '06-JAN-19',
	  to_timestamp('06-JAN-19 10:10:01', 'DD-MON-YY HH24:MI:SS.FF'),
	  to_timestamp('06-JAN-19 10:20:01', 'DD-MON-YY HH24:MI:SS.FF')
	);

	INSERT INTO Visit
	VALUES
	(
	  '2',
	  '20-JAN-19',
	  to_timestamp('20-JAN-19 18:30:33.12', 'DD-MON-YY HH24:MI:SS.FF'),
	  to_timestamp('20-JAN-19 18:56:13.34', 'DD-MON-YY HH24:MI:SS.FF')
	);

	INSERT INTO Visit
	VALUES
	(
	  '3',
	  '26-JAN-19',
	  to_timestamp('26-JAN-19 00:00:10.01', 'DD-MON-YY HH24:MI:SS.FF'),
	  to_timestamp('26-JAN-19 00:05:26.03', 'DD-MON-YY HH24:MI:SS.FF')
	);

	INSERT INTO Visit
	VALUES
	(
	  '4',
	  '27-JAN-19',
	  to_timestamp('27-JAN-19 08:08:41.00', 'DD-MON-YY HH24:MI:SS.FF'),
	  to_timestamp('27-JAN-19 09:20:46.10', 'DD-MON-YY HH24:MI:SS.FF')
	);

	INSERT INTO Visit
	VALUES
	(
	  '5',
	  '30-JAN-19',
	  to_timestamp('30-JAN-19 22:34:32.22', 'DD-MON-YY HH24:MI:SS.FF'),
	  to_timestamp('30-JAN-19 22:40:15.39', 'DD-MON-YY HH24:MI:SS.FF')
	);

	INSERT INTO Student
	VALUES
	(
	  '1',
	  'Hi There',
	  'BCOM',
	  '123 Hi Avenue',
	  (SELECT postalCode from PostalCode WHERE postalCode='V6T 1Z1')
	);

	INSERT INTO Student
	VALUES
	(
	  '2',
	  'Uh Oh',
	  'BUCS',
	  '123 Sad Street',
	  (SELECT postalCode from PostalCode WHERE postalCode='V6T 1Z2')
	);

	INSERT INTO Student
	VALUES
	(
	  '3',
	  'Ex Dee',
	  'BKIN',
	  '123 Troll Boulevard',
	  (SELECT postalCode from PostalCode WHERE postalCode='V6T 1Z1')
	);

	INSERT INTO Student
	VALUES
	(
	  '4',
	  'Keanu Reaves',
	  'BSC',
	  '123 Awesome Avenue',
	  (SELECT postalCode from PostalCode WHERE postalCode='V6T 1Z5')
	);

	INSERT INTO Student
	VALUES
	(
	  '5',
	  'Angelica Schuyler',
	  'BCOM',
	  '123 Hamilton Way',
	  (SELECT postalCode from PostalCode WHERE postalCode='V6T 1Z4')
	);

	INSERT INTO Student
	VALUES
	(
	  '6',
	  'Amy Santiago',
	  'BA',
	  '123 Brooklyn Way',
	  (SELECT postalCode from PostalCode WHERE postalCode='V6T 1Z3')
	);

	INSERT INTO resourceBasedAt
	VALUES
	(
	  'Colour Connected',
	  'Colour Connected Against Racism is an AMS resource group that works to end racism, and all forms of oppression, discrimination and prejudice',
	  'colourconnectedubc@gmail.com',
	  'M W F 2:00 - 4:00 pm',
	  (SELECT locationID from Location WHERE locationID='1')
	);

	INSERT INTO resourceBasedAt
	VALUES
	(
	  'The Pride Collective',
	  'The Pride Collective is an AMS resource group that offers educational and social services dealing with sexual and gender diversity.',
	  'prideubc@gmail.com',
	  'M - F 10:00 am - 4:00 pm',
	  (SELECT locationID from Location WHERE locationID='2')
	);

	INSERT INTO resourceBasedAt
	VALUES
	(
	  'Social Justice Centre',
	  'The UBC Social Justice Centre aims to serve all students interested in finding progressive solutions to societal and global injustice.',
	  'socialjusticecentre.ubc@gmail.com',
	  'Tu Th 3:00 - 5:00 pm',
	  (SELECT locationID from Location WHERE locationID='3')
	);

	INSERT INTO resourceBasedAt
	VALUES
	(
	  'Women''s Centre',
	  'The Women''s Centre, a student run resource group, has been the voice for women''s needs and issues at UBC for over 40 years.',
	  'https://www.facebook.com/groups/ubcwomenscentre/',
	  NULL,
	  (SELECT locationID from Location WHERE locationID='4')
	);

	INSERT INTO resourceBasedAt
	VALUES
	(
	  'Student Recreation Centre',
	  'UBC Athletics Facilities are available for bookings year round. Bookings must be made at least 10 days in advance.',
	  'src.operations@ubc.ca',
	  'M – F 6:30 am – 11 pm',
	  (SELECT locationID from Location WHERE locationID='5')
	);

	INSERT INTO Club
	VALUES
	(
	  'AIESEC UBC',
	  'AIESEC is the world’s largest youth-led network creating positive impact through personal development and shared global experiences.',
	  'info@aiesec.ca',
	  (SELECT officeNumber from Office WHERE officeNumber='1')
	);

	INSERT INTO Club
	VALUES
	(
	  'AMS Amateur Radio Society',
	  'The club is an excellent place for anyone interested in radio communications to learn and obtain their ham radio license.',
	  've7ubc@gmail.com',
	  (SELECT officeNumber from Office WHERE officeNumber='2')
	);

	INSERT INTO Club
	VALUES
	(
	  'AMS Figure Skating Club At UBC',
	  'We are a group of past competitive figure skaters and like to keep up our skills up while having fun together on and off the ice.',
	  'https://www.facebook.com/ubcfigureskatingclub/',
	  (SELECT officeNumber from Office WHERE officeNumber='3')
	);

	INSERT INTO Club
	VALUES
	(
	  'AMS Psychedelic Community',
	  'We are a group of passionate individuals who recognize the ability of psychedelic substances to facilitate altered states of consciousness.',
	  'https://www.facebook.com/groups/ubcpsychedelics',
	  (SELECT officeNumber from Office WHERE officeNumber='4')
	);

	INSERT INTO Club
	VALUES
	(
	  'AMS Writers Guild',
	  'The AMS Writers Guild seeks to provide a safe and welcoming environment for students to share and receive feedback on creative writing.',
	  'amswritersguild.ubc@gmail.com',
	  (SELECT officeNumber from Office WHERE officeNumber='5')
	);

	INSERT INTO ExecutiveOversees
	VALUES
	(
	  (SELECT studentID from Student WHERE studentID='1'),
	  'Assistant Director of Clubs',
	  '2'
	);

	INSERT INTO ExecutiveOversees
	VALUES
	(
	  (SELECT studentID from Student WHERE studentID='2'),
	  'Director of Clubs',
	  '4'
	);

	INSERT INTO ExecutiveOversees
	VALUES
	(
	  (SELECT studentID from Student WHERE studentID='3'),
	  'Director of Information Technology',
	  '5'
	);

	INSERT INTO ExecutiveOversees
	VALUES
	(
	  (SELECT studentID from Student WHERE studentID='4'),
	  'VP Administration',
	  NULL
	);

	INSERT INTO ExecutiveOversees
	VALUES
	(
	  (SELECT studentID from Student WHERE studentID='5'),
	  'VP Academic University and Affairs',
	  NULL
	);

	INSERT INTO memberOf
	VALUES
	(
	  (SELECT clubName from Club WHERE clubName='AIESEC UBC'),
	  (SELECT studentID from Student WHERE studentID='1')
	);

	INSERT INTO memberOf
	VALUES
	(
	  (SELECT clubName from Club WHERE clubName='AMS Amateur Radio Society'),
	  (SELECT studentID from Student WHERE studentID='2')
	);

	INSERT INTO memberOf
	VALUES
	(
	  (SELECT clubName from Club WHERE clubName='AMS Figure Skating Club At UBC'),
	  (SELECT studentID from Student WHERE studentID='3')
	);

	INSERT INTO memberOf
	VALUES
	(
	  (SELECT clubName from Club WHERE clubName='AMS Psychedelic Community'),
	  (SELECT studentID from Student WHERE studentID='4')
	);

	INSERT INTO memberOf
	VALUES
	(
	  (SELECT clubName from Club WHERE clubName='AMS Writers Guild'),
	  (SELECT studentID from Student WHERE studentID='5')
	);

	INSERT INTO Accesses
	VALUES
	(
	  (SELECT resourceName from resourceBasedAt WHERE resourceName='Colour Connected'),
	  (SELECT studentID from Student WHERE studentID='1'),
	  (SELECT visitID from Visit WHERE visitID='1')
	);

	INSERT INTO Accesses
	VALUES
	(
	  (SELECT resourceName from resourceBasedAt WHERE resourceName='The Pride Collective'),
	  (SELECT studentID from Student WHERE studentID='2'),
	  (SELECT visitID from Visit WHERE visitID='2')
	);

	INSERT INTO Accesses
	VALUES
	(
	  (SELECT resourceName from resourceBasedAt WHERE resourceName='Women''s Centre'),
	  (SELECT studentID from Student WHERE studentID='1'),
	  (SELECT visitID from Visit WHERE visitID='3')
	);

	INSERT INTO Accesses
	VALUES
	(
	  (SELECT resourceName from resourceBasedAt WHERE resourceName='Student Recreation Centre'),
	  (SELECT studentID from Student WHERE studentID='4'),
	  (SELECT visitID from Visit WHERE visitID='4')
	);

	INSERT INTO Accesses
	VALUES
	(
	  (SELECT resourceName from resourceBasedAt WHERE resourceName='Social Justice Centre'),
	  (SELECT studentID from Student WHERE studentID='3'),
	  (SELECT visitID from Visit WHERE visitID='5')
	);

	INSERT INTO businessLocatedAt
	VALUES
	(
	  '1',
	  'Blue Chip Cafe',
	  'Food',
	  'Blue Chip Cafe brings together the very best of Blue Chip cookies and Bernoulli’s Bagels!',
	  '(604) 822-6999',
	  'M-F 7AM-7PM || SAT-SUN: 9AM-5PM',
	  (SELECT locationID from Location WHERE locationID='8')
	);

	INSERT INTO businessLocatedAt
	VALUES
	(
	  '2',
	  'Gallery Patio and Lounge',
	  'Food',
	  'The Gallery Patio and Lounge serves Pacific Northwest-inspired food with a strong focus on local ingredients.',
	  '(604) 827-5660',
	  'M-F: 11AM-9PM',
	  (SELECT locationID from Location WHERE locationID='7')
	);

	INSERT INTO businessLocatedAt
	VALUES
	(
	  '3',
	  'Porch',
	  'Food',
	  'Porch is your go-to place for fresh, delicious, comfort vegetarian and vegan food.',
	  '(604) 822 0126',
	  'M-F: 10:30AM-3:00PM',
	  (SELECT locationID from Location WHERE locationID='8')
	);

	INSERT INTO businessLocatedAt
	VALUES
	(
	  '4',
	  'PI[E]R² PIZZA',
	  'Food',
	  'UBC’s favourite pizza restaurant, with great lighting and lots of seating available.',
	  '(604) 822-4396',
	  'M-S: 11AM-7PM',
	  (SELECT locationID from Location WHERE locationID='8')
	);

	INSERT INTO businessLocatedAt
	VALUES
	(
	  '5',
	  'The Delly',
	  'Food',
	  'Freshly-made sandwiches, aromatic curries, and pastries.',
	  '(604) 228-8121',
	  'M-F 8:00AM-7:00PM',
	  (SELECT locationID from Location WHERE locationID='9')
	);

	INSERT INTO eventHappensAtRunBy
	VALUES
	(
	  'Event 1',
	  '06-JAN-19',
	  'a fun event',
	  'free',
	  (SELECT locationID from Location WHERE locationID='8'),
	  (SELECT executiveID from ExecutiveOversees WHERE executiveID='1')
	);

	INSERT INTO eventHappensAtRunBy
	VALUES
	(
	  'Event 2',
	  '20-JAN-19',
	  'a fun event',
	  '$12.00',
	  (SELECT locationID from Location WHERE locationID='7'),
	  (SELECT executiveID from ExecutiveOversees WHERE executiveID='2')
	);

	INSERT INTO eventHappensAtRunBy
	VALUES
	(
	  'Event 3',
	  '26-JAN-19',
	  'a fun event',
	  '$50',
	  (SELECT locationID from Location WHERE locationID='6'),
	  (SELECT executiveID from ExecutiveOversees WHERE executiveID='1')
	);

	INSERT INTO eventHappensAtRunBy
	VALUES
	(
	  'Event 4',
	  '22-JAN-19',
	  'a fun event',
	  'free',
	  (SELECT locationID from Location WHERE locationID='8'),
	  (SELECT executiveID from ExecutiveOversees WHERE executiveID='1')
	);

	INSERT INTO eventHappensAtRunBy
	VALUES
	(
	  'Event 5',
	  '27-JAN-19',
	  'a fun event',
	  'by donation',
	  (SELECT locationID from Location WHERE locationID='8'),
	  (SELECT executiveID from ExecutiveOversees WHERE executiveID='1')
	);

	INSERT INTO BookingReserves
	VALUES
	(
	  '1',
	  (SELECT studentID from Student WHERE studentID='1'),
	  '06-JAN-19',
	  '06-JAN-19',
	  'approved',
	  (SELECT locationID from Location WHERE locationID='8')
	);

	INSERT INTO BookingReserves
	VALUES
	(
	  '2',
	  (SELECT studentID from Student WHERE studentID='4'),
	  '06-JAN-19',
	  '06-JAN-19',
	  'denied',
	  (SELECT locationID from Location WHERE locationID='8')
	);

	INSERT INTO BookingReserves
	VALUES
	(
	  '3',
	  (SELECT studentID from Student WHERE studentID='2'),
	  '17-JAN-19',
	  '18-JAN-19',
	  'requested',
	  (SELECT locationID from Location WHERE locationID='4')
	);

	INSERT INTO BookingReserves
	VALUES
	(
	  '4',
	  (SELECT studentID from Student WHERE studentID='1'),
	  '16-JAN-19',
	  '16-JAN-19',
	  'requested',
	  (SELECT locationID from Location WHERE locationID='3')
	);

	INSERT INTO BookingReserves
	VALUES
	(
	  '5',
	  (SELECT studentID from Student WHERE studentID='5'),
	  '06-MAR-19',
	  '06-MAR-19',
	  'requested',
	  (SELECT locationID from Location WHERE locationID='7')
	);
