
/*
Reg_No:ENE212-0090/2019
Name: GIDEON KIBET.
*/ -

- Host: 127.0.0.1
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";




--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `whole`
--

CREATE TABLE IF NOT EXISTS `whole` (
  `id` int(10) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `sex` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `whole`
--

INSERT INTO `whole` (`id`, `fname`, `lname`, `email`, `password`, `sex`) VALUES
(1, 'gedion', 'kibet', 'kibet@gmail.com', 'kibet1234', 'male');


