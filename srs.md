Software Requirements Specification (SRS) for VIVIDLY

 1. Introduction

 1.1 Purpose
The purpose of this document is to outline the requirements for the development of VIVIDLY, a social networking platform dedicated to visual arts.

 1.2 Scope
VIVIDLY aims to provide a virtual space for artists, primarily targeted at BMMA students, to showcase, interact, and engage with multimedia artworks.

 1.3 Definitions, Acronyms, and Abbreviations
- BMMA: Bachelor of Multimedia Arts

 2. System Overview

 2.1 System Description
VIVIDLY is a web-based platform designed to facilitate the sharing, exploration, and interaction with visual artworks. It includes features such as user authentication, artwork uploading, interactive profiles, search functionality, and administrative tools.

 2.2 System Features
1. User Authentication
2. Artwork Upload
3. Interactive Profiles
4. Search Functionality
5. Artwork Interaction (Comments, Likes)
6. User Interaction (Edit Artworks, Edit Profiles, Comments, Upload Profile Picture, Change Password, Like Artworks, Delete Comments, Delete Uploaded Artworks, Delete Profile, Send Feedback, Send Review, Donate)
7. Administrator Page (Admin Functionalities)

 3. Functional Requirements

 3.1 User Authentication
1. Users can create accounts with unique usernames and passwords.
2. Users can log in securely to access the platform.

 3.2 Artwork Upload
1. Users can upload visual artworks to their profiles.
2. Artwork uploads include metadata such as title, description, and tags.
3. Artworks are associated with the user's profile.

 3.3 Interactive Profiles
1. User profiles display uploaded artworks.
2. Users can edit their profiles, including profile pictures and bio.
3. Users can follow other artists and be followed.

 3.4 Search Functionality
1. Users can search for specific artists or artworks.
2. Search results are displayed in a user-friendly manner.

 3.5 Artwork Interaction
1. Users can view full-size artworks with detailed descriptions.
2. Users can leave comments on artworks.
3. Users can like artworks.

 3.6 User Interaction
1. Users can edit their artworks and profiles.
2. Users can make comments, upload profile pictures, change passwords, like artworks, delete comments, delete uploaded artworks, delete profiles, send feedback, send reviews, and donate.

 3.7 Administrator Page
1. Admins can manage the system, including deleting artworks and user accounts.
2. Admins can track user reviews and feedback.

 4. Non-Functional Requirements

 4.1 Performance
1. The system should handle a minimum of 1000 simultaneous users.
2. Artwork uploads and page loads should not exceed 5 seconds.

 4.2 Security
1. User authentication and data transmission should be secure.
2. Admin functionalities should be accessible only to authorized personnel.

 4.3 Usability
1. The user interface should be intuitive and user-friendly.
2. The platform should be accessible on major web browsers.

 5. Constraints

1. The project must adhere to budgetary constraints.
2. The system should be developed within a specified timeframe.

 6. Risks

1. Potential user privacy concerns.
2. Technical challenges in implementing certain features.

 7. Assumptions and Dependencies

1. Users have a basic understanding of web navigation.
2. The development team has access to necessary resources.


Requirement Elicitation Techniques for VIVIDLY

 Introduction

The Developers recognize the critical importance of accurately gathering and understanding the requirements for VIVIDLY. To ensure a comprehensive and effective requirement elicitation process, the team will employ specific techniques aimed at extracting relevant information from stakeholders.

 1. Surveys and Questionnaires

The Developers will leverage surveys and questionnaires as a primary method for requirement elicitation. The process involves:

- Survey Creation: Develop structured surveys using Google Forms (Exhibit A).
- Distribution: Disseminate surveys among BMMA students, potential users, and administrators.
- Data Gathering: Collect quantitative data on user preferences, expectations, and desired features.

 2. Prototyping

Interactive prototypes will be created to provide stakeholders with a tangible representation of VIVIDLY's interface and functionalities. While the prototypes have not been shared yet, the process includes:

- Prototype Development: Utilize Visual Studio Code (Exhibit A) in conjunction with Apache web server, PHP, and PHPMyAdmin.
- Internal Evaluation: Assess and refine the prototype based on the development team's insights.
- Upcoming Feedback Gathering: Plan to share prototypes with stakeholders for visualizing and interacting with the proposed system.

 3. Brainstorming Sessions

Organized brainstorming sessions will encourage open discussions among team members and stakeholders. The process involves:

- Session Facilitation: Conduct brainstorming sessions to generate creative ideas.
- Idea Documentation: Document ideas and suggestions for potential features and improvements.
- Prioritization: Prioritize ideas based on feasibility and stakeholder relevance.

 4. Focus Groups

Focus groups will be organized with BMMA students and potential users to facilitate group discussions. The process includes:

- Group Formation: Assemble diverse groups of participants.
- Facilitated Discussions: Engage participants in discussions about their expectations and needs.
- Insight Synthesis: Synthesize insights from group discussions to identify common themes and requirements.

 5. Document Analysis

Existing documentation related to multimedia art platforms, social networking sites, and user behavior studies will be analyzed. The process involves:

- Document Review: Analyze industry reports, best practices, and related studies.
- Requirement Identification: Extract potential requirements that align with VIVIDLY's goals.

 6. Observation

The development team will observe users interacting with similar platforms. The process includes:

- User Interaction Observation: Observe users navigating through multimedia art platforms.
- Behavior Analysis: Identify implicit requirements based on user behaviors and preferences.

 Tools Used

- Exhibit A: Google Forms: Utilized for creating and distributing surveys and questionnaires.
- Visual Studio Code: Utilized as the integrated development environment (IDE) for coding and prototyping.
- Prototyping Tools: Utilized in conjunction with Apache web server, PHP, and PHPMyAdmin for creating interactive prototypes.

The Developers are committed to employing these requirement elicitation techniques and tools to ensure a holistic and accurate understanding of stakeholder needs and expectations for the successful development of VIVIDLY.


DFD

1. Processes:
   - Process 1: User Access
     - Input: User accessing the website
     - Output: Redirect to index.php

   - Process 2: Check Login Status
     - Input: User on index.php
     - Output: If not logged in, redirect to login page; if logged in, proceed to index.php

   - Process 3: Navigation to Different Areas
     - Input: User on index.php
     - Output: Options to navigate to Home Page, Discover Page, Posting Page, Artists Page, Notification Page, and More Page

   - Process 4: Search Functionality
     - Input: User input in the search bar
     - Output: Search results for artists or artworks

   - Process 5: User Account Options
     - Input: User interacting with their profile image
     - Output: Options to go to Profile, Log Out, or perform account-related actions

   - Process 6: Artwork Details
     - Input: User clicking on an artwork
     - Output: Display full-size artwork, comments, artist details, and the option to like or comment

   - Process 7: User Interaction with Artwork
     - Input: User liking, commenting, or editing their artwork
     - Output: Update in the system based on the user's action

   - Process 8: Admin Actions
     - Input: Administrator accessing the admin panel
     - Output: Ability to manage the system, including deleting artwork, managing users, tracking reviews, etc.

2. Data Stores:
   - Data Store 1: User Data
     - Stores user information, including login status, profile details, and uploaded artworks

   - Data Store 2: Artwork Data
     - Stores details about each artwork, including artist information, comments, likes, and edits

3. External Entities:
   - External Entity 1: Users
     - Interacts with the system through the website interface

4. Data Flows:
   - User interactions, navigation, and searches result in data flows between processes, data stores, and external entities.