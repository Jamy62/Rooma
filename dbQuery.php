<?php 
    include('dbConnect.php');

    // $createTable = 
    // "CREATE TABLE admins
    // (
    //     AdminID int NOT NULL PRIMARY KEY Auto_Increment,
    //     AdminEmail varchar(30),
    //     AdminName varchar(30),
    //     AdminPp text,
    //     AdminPassword varchar(30),
    //     AdminPh varchar(20)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "Admins table set up successfully.";
    // }

    // else
	// {
	// 	echo "Admins table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE users
    // (
    //     UserID int NOT NULL PRIMARY KEY Auto_Increment,
    //     UserEmail varchar(30),
    //     UserName varchar(30),
    //     UserPp text,
    //     UserPassword varchar(30),
    //     UserPh varchar(20),
    //     UserBalance decimal,
    //     HostVerificationStatus varchar(10),
    //     GuestVerificationStatus varchar(10)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "Users table set up successfully.";
    // }

    // else
	// {
	// 	echo "Users table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE paymenttypes
    // (
    //     PaymentTypeID int NOT NULL PRIMARY KEY Auto_Increment,
    //     PaymentTypeName varchar(30)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "PaymentTypes table set up successfully.";
    // }

    // else
	// {
	// 	echo "PaymentTypes table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE paymentinfo
    // (
    //     PaymentID int NOT NULL PRIMARY KEY Auto_Increment,
    //     UserAccountType varchar(10),
    //     PaymentNo varchar(20),
    //     CardHolderName varchar(30),
    //     CardNumber varchar(30),
    //     CardExpirationDate date,
    //     CardCVV int,
    //     CardBrand varchar(30),
    //     BillingCountry varchar(30),
    //     BillingAddress varchar(50),
    //     BillingPostalCode varchar(10),
    //     PaymentTypeID int,
    //     UserID int,
    //     FOREIGN KEY (PaymentTypeID) REFERENCES paymenttypes(PaymentTypeID),
    //     FOREIGN KEY (UserID) REFERENCES users(UserID)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "PaymentInfo table set up successfully.";
    // }

    // else
	// {
	// 	echo "PaymentInfo table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE verifications
    // (
    //     VerificationID int NOT NULL PRIMARY KEY Auto_Increment,
    //     UserAccountType varchar(10),
    //     LegalName varchar(30),
    //     Selfie text,
    //     IDNumber varchar(30),
    //     IDFront text,
    //     IDBack text,
    //     IDType varchar(30),
    //     VerificationDate date,
    //     VerificationStatus varchar(10),
    //     UserID int,
    //     AdminID int,
    //     CountryID int,
    //     FOREIGN KEY (UserID) REFERENCES users(UserID),
    //     FOREIGN KEY (AdminID) REFERENCES admins(AdminID),
    //     FOREIGN KEY (CountryID) REFERENCES countries(CountryID)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "Verifications table set up successfully.";
    // }

    // else
	// {
	// 	echo "Verifications table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE countries
    // (
    //     CountryID int NOT NULL PRIMARY KEY Auto_Increment,
    //     CountryName varchar(30)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "Country table set up successfully.";
    // }

    // else
	// {
	// 	echo "Country table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE cities
    // (
    //     CityID int NOT NULL PRIMARY KEY Auto_Increment,
    //     CityName varchar(30),
    //     CountryID int,
    //     FOREIGN KEY (CountryID) REFERENCES countries(CountryID)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "Cities table set up successfully.";
    // }

    // else
	// {
	// 	echo "Cities table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE cancellationpolicies
    // (
    //     CancelPolicyID int NOT NULL PRIMARY KEY Auto_Increment,
    //     CancelPolicyName varchar(30),
    //     CancelPolicyDescription varchar(100)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "CancellationPolicies table set up successfully.";
    // }

    // else
	// {
	// 	echo "CancellationPolicies table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE properties
    // (
    //     PropertyID int NOT NULL PRIMARY KEY Auto_Increment,
    //     PropertyImage1 text,
    //     PropertyImage2 text,
    //     PropertyImage3 text,
    //     PropertyAddress varchar(50),
    //     PropertyCoordinates varchar(30),
    //     PropertyRooms varchar(50),
    //     PropertyDescription varchar(100),
    //     PropertyListingDate date,
    //     PropertyStatus varchar(10),
    //     PropertyPrice decimal,
    //     CityID int,
    //     CancelPolicyID int,
    //     AdminID int,
    //     FOREIGN KEY (CityID) REFERENCES cities(CityID),
    //     FOREIGN KEY (CancelPolicyID) REFERENCES cancellationpolicies(CancelPolicyID),
    //     FOREIGN KEY (AdminID) REFERENCES admins(AdminID)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "Properties table set up successfully.";
    // }

    // else
	// {
	// 	echo "Properties table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE rules
    // (
    //     RuleID int NOT NULL PRIMARY KEY Auto_Increment,
    //     RuleName varchar(30),
    //     RuleIcon text,
    //     RuleDescription varchar(100)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "Rules table set up successfully.";
    // }

    // else
	// {
	// 	echo "Rules table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE propertyrules
    // (
    //     PropertyID int,
    //     RuleID int,
    //     FOREIGN KEY (PropertyID) REFERENCES properties(PropertyID),
    //     FOREIGN KEY (RuleID) REFERENCES rules(RuleID)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "PropertyRules table set up successfully.";
    // }

    // else
	// {
	// 	echo "PropertyRules table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE propertyhosts
    // (
    //     UserID int,
    //     PropertyID int,
    //     FOREIGN KEY (UserID) REFERENCES users(UserID),
    //     FOREIGN KEY (PropertyID) REFERENCES properties(PropertyID)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "PropertyHosts table set up successfully.";
    // }

    // else
	// {
	// 	echo "PropertyHosts table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE bookings
    // (
    //     BookingID int NOT NULL PRIMARY KEY Auto_Increment,
    //     BookingDate date,
    //     BookingGuestQty int,
    //     BookingMessage varchar(255),
    //     BookingCheckInDate date,
    //     BookingCheckOutDate date,
    //     BookingTotalNights int,
    //     BookingTotalPrice decimal,
    //     BookingStatus varchar(10),
    //     BookingServiceFee decimal,
    //     BookingFinishStatus varchar(10),
    //     BookingReview varchar(255),
    //     PaymentID int,
    //     PropertyID int,
    //     UserID int,
    //     FOREIGN KEY (PaymentID) REFERENCES paymentinfo(PaymentID),
    //     FOREIGN KEY (PropertyID) REFERENCES properties(PropertyID),
    //     FOREIGN KEY (UserID) REFERENCES users(UserID)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "Bookings table set up successfully.";
    // }

    // else
	// {
	// 	echo "Bookings table set up failed.";
	// }



    // $createTable = 
    // "CREATE TABLE messages
    // (
    //     MessageID int NOT NULL PRIMARY KEY Auto_Increment,
    //     MessageTimeSent timestamp,
    //     MessageText varchar(255),
    //     MessageSenderID int,
    //     MessageReceiverID int,
    //     FOREIGN KEY (MessageSenderID) REFERENCES users(UserID),
    //     FOREIGN KEY (MessageReceiverID) REFERENCES users(UserID)
    // )";

    // $result = mysqli_query($dbConnect, $createTable);

    // if ($result) 
    // {
    //     echo "Messages table set up successfully.";
    // }

    // else
	// {
	// 	echo "Messages table set up failed.";
	// }