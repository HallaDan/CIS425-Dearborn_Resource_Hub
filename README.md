# Multilingual Professional Directory for Dearborn

## Project Overview

### Core Idea
This project is a web-based platform aimed at connecting residents of Dearborn with professionals who speak multiple languages, including English, Arabic, and Spanish. The platform addresses language barriers in accessing essential services like legal aid, plumbing, and mechanics. Both professionals and users can contribute to the directory, creating a dynamic resource for the community.

## Features and Functionality

### Literature Review / Competitive Analysis

#### Competitive Landscape
The platform’s main competitors are Facebook and WhatsApp community groups. Our website distinguishes itself by providing wider access, especially for those who may not be on social media, such as older adults or newcomers to Dearborn. The platform will also feature a community events page and a business listings directory, both of which accept user contributions upon admin approval.

### Unique Value Proposition
The website enables users to locate professionals based on language and profession, removing the need to rely on word-of-mouth or assumptions. For instance, a user can find a lawyer who speaks Arabic or a plumber who speaks Spanish directly on the platform.

## Implementation Plan

### Modules / Entities

#### Professional Profiles
Professionals can log in to create profiles that include:
- Name
- Profile picture
- Languages spoken
- Area of expertise
- Years of experience
- Contact details (phone number, email, business address)

#### User Contributions
Users can submit listings for professionals in the area. These contributions will require admin approval after verification.

#### Directory Navigation
A language and profession-based navigation system (e.g., drop-down menu or buttons) allows users to easily find professionals who meet their needs.

### User Roles and Access Control

#### Unregistered Users
- Can view all professional listings and user-contributed listings on the Business Listing Page.
- Cannot contribute listings on the User Contribution Page.

#### Professional Users
- Can view all listings on the Business Listing Page.
- Can submit requests for themselves to be listed on the Business Listing Page.
- Can request the removal of their listings from the Business Listing Page.

#### Registered Users
- Can view all listings.
- Can submit requests for new listings on the Business Listing Page via the User Contribution Page.
- Can request to delete listings they have contributed.

### Project Prototyping
Three key pages are designed using Figma to reflect the color theme and user interface considerations:

#### Landing Page
- Welcome message
- Brief platform description
- Language selection option
- Navigation tabs and search bar

#### Business Listing Page
- Directory of businesses and professionals
- Contribution option for users
- Search bar

#### User Contribution Page
- Login or account creation prompt
- Fields for submitting a new professional listing

## Technology Stack

### Backend
- **PHP**: Handles server-side logic, including user authentication, form submissions, and profile management.
- **MySQL**: Manages data storage for user profiles, business listings, and contributions.

### Frontend
- **HTML**, **CSS**, **JavaScript**: Used for building the front-end interface, enabling language-based navigation and profile submissions.

### Additional Tools
- **Figma**: Used for designing key pages and planning the visual layout of the site.

## Installation and Setup

### 1. Clone the Repository
```bash
git clone <repository-url>
cd project-directory
```

### 2. Database Setup
- Import the provided SQL files into MySQL.
- Configure database credentials in the PHP configuration file.

### 3. Start Local Server
- Use Apache (or similar server) to host the PHP files.
  
### 4. Access the Website
- Navigate to `localhost/<project-folder>` to access the website locally.

## Future Improvements
- **Built-in Translator**: A translator tool (e.g., pop-up support with ChatGPT) for enhanced multilingual support.
- **Community Events Page**: An event listing page that allows users to post local events upon admin approval.
-  **Advanced Search Filters**: Filtering options by language, profession, and availability.
