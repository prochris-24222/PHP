# STIC 2026 Conference Registration

An individual Web Application Development II CAT project for the Student Technology and Innovation Conference. It includes a home page, registration form, live fee calculator, browser-side validation, and PHP confirmation page.

## Included assignment requirements

- Admission-number last digit: **2**.
- Digit 2 feature: an optional second workshop costing **KSh 1,000**.
- Both JavaScript and PHP include the second workshop in the total.
- PHP validates submitted values and calculates the amount itself instead of trusting a browser total.

## Run locally

1. Install or open XAMPP/WAMP with Apache and PHP.
2. Copy the `ConferenceProject` folder into the server's web root (for XAMPP, `htdocs`).
3. Start Apache.
4. Open `http://localhost/ConferenceProject/` in a browser.
5. Register a sample participant and submit the form to view the PHP confirmation.

The PHP confirmation will not work by opening the HTML file directly. Serve the folder through Apache or another PHP-enabled local server.

## Fees

| Item | Charge |
|---|---:|
| Conference registration | KSh 1,000 |
| Attendance | KSh 500 per day |
| First workshop | KSh 750 |
| Second workshop (digit 2 requirement) | KSh 1,000 |
| Lunch | KSh 300 per day |

## Demonstration snapshots

The CAT asks for snapshots of the running application, including validation, a changing estimate, and successful PHP submission. Capture these after starting the project in XAMPP/WAMP. Suggested captures:

1. `01-home.png` — conference landing page.
2. `02-validation.png` — submit an empty form and show the validation messages.
3. `03-live-calculation.png` — choose 2 days, one workshop, the second workshop, and lunch. The total should be **KSh 4,850** (1,000 + 1,000 + 750 + 1,000 + 600).
4. `04-confirmation.png` — submit valid sample details and show the PHP-generated confirmation with the same total.

Keep screenshots in a `snapshots` folder in the project ZIP. Use sample data rather than personal contact information.

## Submission

Rename the final ZIP as `AdmissionNumber_WADII_Project.zip`, replacing `AdmissionNumber` with your own admission number.
