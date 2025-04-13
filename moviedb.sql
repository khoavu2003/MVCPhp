-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 13, 2025 lúc 05:48 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `moviedb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `actor`
--

CREATE TABLE `actor` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `birthDate` date DEFAULT NULL,
  `birthPlace` varchar(255) DEFAULT '',
  `description` text DEFAULT '',
  `profileImage` varchar(255) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `actor`
--

INSERT INTO `actor` (`id`, `name`, `birthDate`, `birthPlace`, `description`, `profileImage`, `createdAt`) VALUES
(1, 'Leonardo DiCaprio', '1974-11-11', 'Los Angeles, CA, USA', 'Diễn viên nổi tiếng với vai diễn trong Titanic và Inception.', 'leonardo_profile.jpg', '2025-04-07 06:20:56'),
(2, 'Scarlett Johansson', '1984-11-22', 'New York, NY, USA', 'Nữ diễn viên nổi tiếng với vai Black Widow trong MCU.', 'scarlett_profile.jpg', '2025-04-07 06:20:56'),
(3, 'Brad Pitt', '1963-12-18', 'Shawnee, OK, USA', 'Diễn viên và nhà sản xuất nổi tiếng với Fight Club và Once Upon a Time in Hollywood.', 'brad_profile.jpg', '2025-04-07 06:20:56'),
(4, 'Tom Hanks', '1956-07-09', 'Concord, CA, USA', 'Diễn viên kỳ cựu với các vai trong Forrest Gump và Cast Away.', 'tom_profile.jpg', '2025-04-07 06:20:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `actorphotos`
--

CREATE TABLE `actorphotos` (
  `id` int(11) NOT NULL,
  `actorId` int(11) NOT NULL,
  `photoUrl` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `actorphotos`
--

INSERT INTO `actorphotos` (`id`, `actorId`, `photoUrl`) VALUES
(1, 1, 'leonardo_photo1.jpg'),
(2, 1, 'leonardo_photo2.jpg'),
(3, 2, 'scarlett_photo1.jpg'),
(4, 3, 'brad_photo1.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `genre`
--

INSERT INTO `genre` (`id`, `name`, `createdAt`) VALUES
(1, 'Action', '2025-04-07 06:20:57'),
(2, 'Drama', '2025-04-07 06:20:57'),
(3, 'Sci-Fi', '2025-04-07 06:20:57'),
(4, 'Comedy', '2025-04-07 06:20:57'),
(5, 'Romance', '2025-04-07 06:20:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `movie`
--

CREATE TABLE `movie` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `releaseYear` int(11) NOT NULL,
  `director` varchar(255) NOT NULL,
  `poster` varchar(255) NOT NULL,
  `trailer` varchar(255) DEFAULT NULL,
  `theatricalReleaseDate` date DEFAULT NULL,
  `bannerImage` varchar(255) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `movie`
--

INSERT INTO `movie` (`id`, `title`, `description`, `releaseYear`, `director`, `poster`, `trailer`, `theatricalReleaseDate`, `bannerImage`, `createdAt`) VALUES
(1, 'Inception', 'Một tên trộm chuyên nghiệp có khả năng xâm nhập vào giấc mơ để đánh cắp bí mật.', 2010, 'Christopher Nolan', 'https://image.tmdb.org/t/p/w500/yFHHfHcUgGAxziP1C3lLt0q2T4s.jpg', 'https://www.youtube.com/watch?v=8B1EtVPBSMw', '2010-07-16', 'https://image.tmdb.org/t/p/w1280/2Nti3gYAX513wvhp8IiLL6ZDyOm.jpg', '2025-04-07 06:20:57'),
(2, 'The Avengers', 'Nhóm siêu anh hùng chiến đấu để cứu Trái Đất khỏi Loki.', 2012, 'Joss Whedon', 'https://image.tmdb.org/t/p/w500/yFHHfHcUgGAxziP1C3lLt0q2T4s.jpg', 'https://www.youtube.com/watch?v=8B1EtVPBSMw', '2012-05-04', 'https://image.tmdb.org/t/p/w1280/2Nti3gYAX513wvhp8IiLL6ZDyOm.jpg', '2025-04-07 06:20:57'),
(3, 'Titanic', 'Câu chuyện tình yêu đầy bi kịch trên con tàu định mệnh.', 1997, 'James Cameron', 'https://image.tmdb.org/t/p/w500/yFHHfHcUgGAxziP1C3lLt0q2T4s.jpg', 'https://www.youtube.com/watch?v=8B1EtVPBSMw', '1997-12-19', 'https://image.tmdb.org/t/p/w1280/2Nti3gYAX513wvhp8IiLL6ZDyOm.jpg', '2025-04-07 06:20:57'),
(4, 'Forrest Gump', 'Hành trình cuộc đời kỳ diệu của một người đàn ông đơn giản.', 1994, 'Robert Zemeckis', 'https://image.tmdb.org/t/p/w500/yFHHfHcUgGAxziP1C3lLt0q2T4s.jpg', 'https://www.youtube.com/watch?v=8B1EtVPBSMw', '1994-07-06', 'https://image.tmdb.org/t/p/w1280/2Nti3gYAX513wvhp8IiLL6ZDyOm.jpg', '2025-04-07 06:20:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `movieactor`
--

CREATE TABLE `movieactor` (
  `id` int(11) NOT NULL,
  `movieId` int(11) NOT NULL,
  `actorId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `movieactor`
--

INSERT INTO `movieactor` (`id`, `movieId`, `actorId`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 1),
(4, 4, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `moviecomments`
--

CREATE TABLE `moviecomments` (
  `id` int(11) NOT NULL,
  `movieId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `moviecomments`
--

INSERT INTO `moviecomments` (`id`, `movieId`, `userId`, `comment`, `date`) VALUES
(1, 1, 1, 'Bộ phim này thật sự làm tôi kinh ngạc với cốt truyện phức tạp!', '2025-04-07 06:20:57'),
(2, 2, 3, 'Hành động đỉnh cao, rất đáng xem!', '2025-04-07 06:20:57'),
(3, 3, 1, 'Một câu chuyện tình yêu buồn nhưng đẹp.', '2025-04-07 06:20:57'),
(4, 4, 3, 'Tom Hanks diễn xuất quá tuyệt vời!', '2025-04-07 06:20:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `moviegenre`
--

CREATE TABLE `moviegenre` (
  `id` int(11) NOT NULL,
  `movieId` int(11) NOT NULL,
  `genreId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `moviegenre`
--

INSERT INTO `moviegenre` (`id`, `movieId`, `genreId`) VALUES
(1, 1, 3),
(2, 1, 2),
(3, 2, 1),
(4, 2, 3),
(5, 3, 2),
(6, 3, 5),
(7, 4, 2),
(8, 4, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `movieratings`
--

CREATE TABLE `movieratings` (
  `id` int(11) NOT NULL,
  `movieId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `movieratings`
--

INSERT INTO `movieratings` (`id`, `movieId`, `userId`, `rating`) VALUES
(1, 1, 1, 8),
(2, 2, 3, 9),
(3, 3, 1, 7),
(4, 4, 3, 8);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `movieId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 10),
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ratings`
--

INSERT INTO `ratings` (`id`, `movieId`, `userId`, `rating`, `createdAt`) VALUES
(1, 1, 1, 10, '2025-04-11 08:29:17'),
(2, 2, 1, 9, '2025-04-11 08:34:38'),
(3, 1, 5, 7, '2025-04-11 09:25:43'),
(4, 4, 5, 10, '2025-04-11 09:28:43'),
(5, 3, 5, 9, '2025-04-11 09:30:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Nguyễn Anh Tuấn', 'a@gmail.com', '$2y$10$CvGrXgDlOUHNYRmUTIoC5OjlDTi3JI9Z/4rMBIsQ7nSI25mDXxIm.', 'user'),
(2, 'John Doe', 'john@example.com', '$2y$10$Kj8pX9mQz7vW5rT2uY1e8e4rTj5vX6nY7mQz8pX9rT2uY1e8e4rTj', 'user'),
(3, 'Jane Smith', 'jane@example.com', '$2y$10$Kj8pX9mQz7vW5rT2uY1e8e4rTj5vX6nY7mQz8pX9rT2uY1e8e4rTj', 'admin'),
(4, 'Alice Brown', 'alice@example.com', '$2y$10$Kj8pX9mQz7vW5rT2uY1e8e4rTj5vX6nY7mQz8pX9rT2uY1e8e4rTj', 'user'),
(5, 'Nguyễn Anh Tuấn', 'b@gmail.com', '$2y$10$KHiWSL/WIAI1V2zvWDqB8.SuiwLOmQxBsunk2SeKffPn7sf251dGm', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `watchlist`
--

CREATE TABLE `watchlist` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT '',
  `userId` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `watchlist`
--

INSERT INTO `watchlist` (`id`, `name`, `description`, `userId`, `createdAt`) VALUES
(11, 'Default Watchlist', 'Danh sách phim mặc định.', 1, '2025-04-07 09:13:28'),
(14, 'b', 'b', 1, '2025-04-11 05:53:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `watchlistmovies`
--

CREATE TABLE `watchlistmovies` (
  `id` int(11) NOT NULL,
  `watchlistId` int(11) NOT NULL,
  `movieId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `watchlistmovies`
--

INSERT INTO `watchlistmovies` (`id`, `watchlistId`, `movieId`) VALUES
(16, 11, 4),
(17, 11, 1),
(18, 11, 2);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `actor`
--
ALTER TABLE `actor`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `actorphotos`
--
ALTER TABLE `actorphotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actorId` (`actorId`);

--
-- Chỉ mục cho bảng `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `movieactor`
--
ALTER TABLE `movieactor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movieId` (`movieId`),
  ADD KEY `actorId` (`actorId`);

--
-- Chỉ mục cho bảng `moviecomments`
--
ALTER TABLE `moviecomments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movieId` (`movieId`),
  ADD KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `moviegenre`
--
ALTER TABLE `moviegenre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movieId` (`movieId`),
  ADD KEY `genreId` (`genreId`);

--
-- Chỉ mục cho bảng `movieratings`
--
ALTER TABLE `movieratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movieId` (`movieId`),
  ADD KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `movieId` (`movieId`,`userId`),
  ADD KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `watchlistmovies`
--
ALTER TABLE `watchlistmovies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `watchlistId` (`watchlistId`),
  ADD KEY `movieId` (`movieId`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `actor`
--
ALTER TABLE `actor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `actorphotos`
--
ALTER TABLE `actorphotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `movie`
--
ALTER TABLE `movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `movieactor`
--
ALTER TABLE `movieactor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `moviecomments`
--
ALTER TABLE `moviecomments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `moviegenre`
--
ALTER TABLE `moviegenre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `movieratings`
--
ALTER TABLE `movieratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `watchlistmovies`
--
ALTER TABLE `watchlistmovies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `actorphotos`
--
ALTER TABLE `actorphotos`
  ADD CONSTRAINT `actorphotos_ibfk_1` FOREIGN KEY (`actorId`) REFERENCES `actor` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `movieactor`
--
ALTER TABLE `movieactor`
  ADD CONSTRAINT `movieactor_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movie` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movieactor_ibfk_2` FOREIGN KEY (`actorId`) REFERENCES `actor` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `moviecomments`
--
ALTER TABLE `moviecomments`
  ADD CONSTRAINT `moviecomments_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movie` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `moviecomments_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `moviegenre`
--
ALTER TABLE `moviegenre`
  ADD CONSTRAINT `moviegenre_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movie` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `moviegenre_ibfk_2` FOREIGN KEY (`genreId`) REFERENCES `genre` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `movieratings`
--
ALTER TABLE `movieratings`
  ADD CONSTRAINT `movieratings_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movie` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movieratings_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`movieId`) REFERENCES `movie` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `watchlistmovies`
--
ALTER TABLE `watchlistmovies`
  ADD CONSTRAINT `watchlistmovies_ibfk_1` FOREIGN KEY (`watchlistId`) REFERENCES `watchlist` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `watchlistmovies_ibfk_2` FOREIGN KEY (`movieId`) REFERENCES `movie` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
