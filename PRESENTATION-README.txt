TASKWISE — PRESENTATION BUILD
Mangaldan National High School

HOW TO RUN
1. Extract the ZIP file.
2. Open the "TaskWise website prototype" folder.
3. Open index.html in Chrome or Microsoft Edge.
4. Sign in using one of the demo accounts below.

DEMO ACCOUNTS
Administrator: admin / admin123
Teacher:       teacher / teacher123
Student:       student / student123

SUGGESTED PRESENTATION FLOW
1. Login as Administrator. Show the administrator dashboard, user management,
   assignment overview, and reports/settings sections.
2. Log out, then login as Teacher. Create an assignment with a subject, title,
   instructions, a future due date, and (optionally) a small attachment.
3. Open the Posted Assignments section and show the new assignment.
4. Log out, then login as Student. Show that the teacher's assignment appears.
   Open the assignment and mark it completed.
5. Return to the Teacher dashboard and show the updated completion count.

IMPORTANT PROTOTYPE NOTES
- This is a front-end classroom prototype. It stores data in the browser's
  localStorage; it does not use a server/database or synchronize between
  different computers/browsers.
- For the smoothest demonstration, use the same computer and same browser
  window/profile for all three roles. Do not use Incognito mode.
- The demo passwords are for presentation only. Do not use real passwords.
- Uploaded assignment attachments are stored in browser storage. Keep them
  small (5 MB maximum).
- If you need a clean demo, open Developer Tools > Application > Local Storage
  and clear the TaskWise entries, then reload. This also removes demo-created
  accounts and assignments. The built-in demo accounts are recreated on load.
- Browser speech output depends on the voices installed in the computer.
  The browser may use a different voice if a female-sounding voice is unavailable.

CHANGES IN THIS PRESENTATION BUILD
- Hardened user-data loading so malformed saved account records do not crash login.
- Added assignment due-date validation (today or a future date only).
- Added a 5 MB attachment limit and clearer status messages.
- Added a minimum date to the teacher due-date field.


STUDENT ANSWER SUBMISSION DEMO
1. Log in as Teacher and post an assignment.
2. Log out and log in as Student in the SAME browser.
3. Open Assignments, choose a file containing the student's answer (PDF/DOCX/etc.), then click Submit Answer & Mark Completed.
4. The assignment becomes Completed only after the file is successfully uploaded.
5. Log out and log back in as Teacher, open Posted Assignments, and download the student's submitted answer.
Note: This prototype stores files in browser localStorage. Use small files (maximum 5 MB) and the same browser/profile for this demo. It is not a shared online server/database.


ADMIN CLASS / TEACHER SETUP (NEW)
1. Log in as Admin and open Manage Users.
2. Edit each student account and set Grade Level, Section, and Class Teacher.
3. Edit each teacher account and set the grade/section they handle (optional reference information).
4. When a teacher creates an assignment, they must select the target Grade Level and may enter a Section.
5. A student sees assignments matching their saved grade and section (or assignments marked All Grades / all sections).
6. Student assignment cards identify the teacher who posted the work and label whether that person is the student's class teacher or another subject teacher.
7. Admin > Assignments shows the posting teacher and target grade/section for every assignment.

IMPORTANT: If a student has no grade/section saved, targeted assignments will not appear for that student. Set the student's profile in Admin first. This prototype uses browser localStorage and is intended for a same-browser demonstration, not multi-device production use.
