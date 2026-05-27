# Debugging Skills Report

*Instructions for Student: This document is a template. You need to read the instructions in brackets `[...]`, follow them to capture your own screenshots, write your explanations, and submit this to your instructor.*

## 1. Inspecting Variables with `dd()`

**Task:** Use `dd()` to inspect a model or request object.

**Steps to Reproduce:**
1. Open `app/Controllers/EventController.php`.
2. Inside the `index()` method, add `dd($this->request->getGet());` right after the method starts.
3. Open your browser and go to `http://localhost:8080/events?search=test`.
4. The page will stop execution and display a formatted dump of the `$_GET` parameters array.
5. Take a screenshot of the dumped output.

**Screenshot of `dd()` output:**
![Insert your screenshot here](path/to/your/screenshot.png)

**Explanation:**
The `dd()` (Dump and Die) function is an essential tool in CodeIgniter 4. It allowed me to immediately halt the script's execution and beautifully format the contents of the incoming GET request. This is critical for verifying that the controller is successfully receiving the query parameters from the frontend before any database operations occur.

---

## 2. Analyzing a Stack Trace

**Task:** Read & explain a stack trace (screenshot + annotation).

**Steps to Reproduce:**
1. Open `app/Controllers/DashboardController.php`.
2. Inside the `admin()` method, temporarily misspell a method call (e.g., change `countAllResults()` to `countAllResultzz()`).
3. Refresh the `http://localhost:8080/admin/dashboard` page.
4. CodeIgniter 4's Whoops error handler will appear showing an error like `Call to undefined method`.
5. Take a screenshot of the error page clearly showing the highlighted line of code causing the crash.

**Screenshot of the Stack Trace:**
![Insert your stack trace screenshot here](path/to/your/screenshot.png)

**Explanation of the Trace:**
The stack trace indicated that the error originated in `DashboardController.php` at line `[Your Line Number]`. The framework successfully routed the request through the `index.php` front controller and the router, but execution halted when it attempted to call a method that did not exist on the Model instance. The trace provided the exact file path and line number, making it trivial to locate the typo.

---

## 3. Fixing a Real Bug

**Task:** Document what was wrong & how you found it.

**Bug Description:**
During testing, I noticed that the Event search pagination wasn't maintaining the search parameters when moving to page 2.

**How I Found It:**
By using `dd($this->request->getGet())` on the second page of the search results, I observed that the `search` key was completely missing from the request array, confirming that the pagination links were dropping the query string.

**The Fix:**
I opened `app/Config/Pager.php` and located the `$useQueryString` property. It was set to `false` by default. By changing it to `true`, the Pager library was instructed to append existing GET parameters to all generated pagination URLs.

```diff
// app/Config/Pager.php
- public bool $useQueryString = false;
+ public bool $useQueryString = true;
```

**Verification:**
After the fix, clicking "Next Page" while searching correctly generated a URL like `?page=2&search=test` and the results correctly matched the filtered query.
