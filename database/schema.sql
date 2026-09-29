-- MASARAK Database Schema
-- Create Database
CREATE DATABASE IF NOT EXISTS masarak_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE masarak_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    language VARCHAR(10) DEFAULT 'ar',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Profiles Table
CREATE TABLE IF NOT EXISTS user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    city VARCHAR(100),
    graduation_year INT(4),
    gender ENUM('male', 'female') NOT NULL,
    phone VARCHAR(20),
    age INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_profile (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Academic Data Table
CREATE TABLE IF NOT EXISTS academic_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    qiyas_score DECIMAL(5,2),
    tahsili_score DECIMAL(5,2),
    high_school_gpa DECIMAL(5,2),
    weighted_score DECIMAL(5,2),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_academic (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Interests Survey Table
CREATE TABLE IF NOT EXISTS interests_survey (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    preferred_field ENUM('technology', 'health', 'engineering', 'arts', 'tourism', 'law') NOT NULL,
    preferred_subjects TEXT,
    interest_nature ENUM('analytical', 'creative', 'helping_others') NOT NULL,
    work_environment ENUM('office', 'field', 'laboratory', 'creative_space') NOT NULL,
    additional_interests TEXT,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_survey (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recommendations Table
CREATE TABLE IF NOT EXISTS recommendations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    major_name_ar VARCHAR(100) NOT NULL,
    major_name_en VARCHAR(100) NOT NULL,
    college_ar VARCHAR(100) NOT NULL,
    college_en VARCHAR(100) NOT NULL,
    recommendation_reason_ar TEXT,
    recommendation_reason_en TEXT,
    acceptance_probability ENUM('high', 'medium', 'low') NOT NULL,
    match_score DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Majors Reference Table
CREATE TABLE IF NOT EXISTS majors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    major_code VARCHAR(20) UNIQUE NOT NULL,
    major_name_ar VARCHAR(100) NOT NULL,
    major_name_en VARCHAR(100) NOT NULL,
    college_ar VARCHAR(100) NOT NULL,
    college_en VARCHAR(100) NOT NULL,
    field_category ENUM('technology', 'health', 'engineering', 'arts', 'tourism', 'law') NOT NULL,
    min_weighted_score DECIMAL(5,2),
    description_ar TEXT,
    description_en TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Majors Data
INSERT INTO majors (major_code, major_name_ar, major_name_en, college_ar, college_en, field_category, min_weighted_score, description_ar, description_en) VALUES
('CS', 'علوم الحاسب', 'Computer Science', 'كلية التقنية', 'College of Technology', 'technology', 80.00, 'تخصص يركز على البرمجة وتطوير البرمجيات', 'Focuses on programming and software development'),
('IS', 'نظم المعلومات', 'Information Systems', 'كلية التقنية', 'College of Technology', 'technology', 75.00, 'تخصص يجمع بين التقنية والإدارة', 'Combines technology and management'),
('SE', 'هندسة البرمجيات', 'Software Engineering', 'كلية التقنية', 'College of Technology', 'technology', 85.00, 'تخصص في هندسة وتطوير الأنظمة البرمجية', 'Specializes in engineering and developing software systems'),
('MED', 'طب', 'Medicine', 'كلية الصحة', 'College of Health', 'health', 95.00, 'تخصص طبي شامل', 'Comprehensive medical specialty'),
('NUR', 'تمريض', 'Nursing', 'كلية الصحة', 'College of Health', 'health', 70.00, 'تخصص في الرعاية التمريضية', 'Specialty in nursing care'),
('DEN', 'أسنان', 'Dentistry', 'كلية الصحة', 'College of Health', 'health', 90.00, 'تخصص في طب الأسنان', 'Specialty in dentistry'),
('CE', 'هندسة مدنية', 'Civil Engineering', 'كلية الهندسة', 'College of Engineering', 'engineering', 85.00, 'تخصص في البناء والبنية التحتية', 'Specialty in construction and infrastructure'),
('EE', 'هندسة كهربائية', 'Electrical Engineering', 'كلية الهندسة', 'College of Engineering', 'engineering', 85.00, 'تخصص في الأنظمة الكهربائية', 'Specialty in electrical systems'),
('IE', 'هندسة صناعية', 'Industrial Engineering', 'كلية الهندسة', 'College of Engineering', 'engineering', 80.00, 'تخصص في تحسين العمليات الصناعية', 'Specialty in improving industrial processes'),
('ARCH', 'عمارة', 'Architecture', 'كلية الفنون', 'College of Arts', 'arts', 80.00, 'تخصص في التصميم المعماري', 'Specialty in architectural design'),
('FD', 'تصميم أزياء', 'Fashion Design', 'كلية الفنون', 'College of Arts', 'arts', 70.00, 'تخصص في تصميم الأزياء', 'Specialty in fashion design'),
('TH', 'سياحة وإدارة ضيافة', 'Tourism and Hospitality Management', 'كلية السياحة', 'College of Tourism', 'tourism', 65.00, 'تخصص في إدارة السياحة والضيافة', 'Specialty in tourism and hospitality management'),
('TM', 'إدارة سياحية', 'Tourism Management', 'كلية السياحة', 'College of Tourism', 'tourism', 65.00, 'تخصص في إدارة الأعمال السياحية', 'Specialty in tourism business management'),
('LAW', 'قانون', 'Law', 'كلية الحقوق', 'College of Law', 'law', 75.00, 'تخصص في القانون والأنظمة', 'Specialty in law and regulations');
