You are an expert in PHP, Laravel, Pest,Vue.js and Tailwind.

1. Coding Standards
   •	Use PHP v8.4 features.
   •	Follow pint.json coding rules.
   •	Enforce strict types and array shapes via PHPStan.

2. Project Structure & Architecture
   •	Delete .gitkeep when adding a file.
   •	Stick to existing structure—no new folders.
   •	Avoid DB::; use Model::query() only.
   •	No dependency changes without approval.

2.1 Directory Conventions

app/Http/Controllers
•	No abstract/base controllers.

app/Http/Requests
•	Use FormRequest for validation.
•	Name with Create, Update, Delete.

app/Actions
•	Use Actions pattern and naming verbs.
•	Example:

```php
public function store(CreateTodoRequest $request, CreateTodoAction $action)
{
    $user = $request->user();

    $action->handle($user, $request->validated());
}
```

app/Models
•	Avoid fillable.

database/migrations
•	Omit down() in new migrations.

3. Testing
   •	Use Pest PHP for all tests.
   •	Run composer lint after changes.
   •	Run composer test before finalizing.
   •	Don’t remove tests without approval.
   •	All code must be tested.
   •	Generate a {Model}Factory with each model.

3.1 Test Directory Structure
•	Console: tests/Feature/Console
•	Controllers: tests/Feature/Http
•	Actions: tests/Unit/Actions
•	Models: tests/Unit/Models
•	Jobs: tests/Unit/Jobs

4. Styling & UI
   •	Use Tailwind CSS.
   •	Keep UI minimal.

5. Task Completion Requirements
   •	Recompile assets after frontend changes.
   •	Follow all rules before marking tasks complete.
6. Vue.js Coding Standards
   •	Use store dispatch to fetch data from API
   •	Use mutations for updating state
   •	Use actions for API calls which fetch data, set to mutation, and use in components
   •	Use Composition API and script setup tag
   •	Implement proper error handling in API calls
   •	Use computed properties for derived state
   •	Implement loading states for async operations
   •	Use proper component lifecycle hooks (onMounted, etc.)
   •	Implement proper form validation
   •	Use Tailwind CSS for styling components

7. Project Implementation Approach
   In this project, we implemented a modern single-page application (SPA) with Vue.js frontend and Laravel backend. Here's a summary of our approach:

   7.1 Frontend Architecture
   •	Vue.js 3 with Composition API for reactive UI components
   •	Vue Router for client-side routing with protected routes
   •	Vuex for centralized state management
   •	Tailwind CSS v4 for utility-first styling
   •	Component-based architecture with reusable UI elements
   •	Cookies for token storage and authentication persistence
   •	Responsive design for mobile and desktop views

   7.2 State Management
   •	Vuex modules for organizing state by feature (auth, users, dashboard)
   •	Actions for asynchronous operations and API calls
   •	Mutations for synchronous state updates
   •	Getters for computed state
   •	Proper error handling and loading states

   7.3 API Integration
   •	RESTful API endpoints for CRUD operations
   •	JWT authentication for secure API access
   •	Proper error handling and response parsing
   •	Centralized API call logic in Vuex actions
   •	Consistent request/response format

   7.4 Authentication & Authorization
   •	JWT-based authentication with token storage in cookies
   •	Role-based access control (admin vs user roles)
   •	Protected routes with navigation guards
   •	Automatic token refresh mechanism
   •	Secure logout process

   7.5 UI/UX Design
   •	Clean, modern interface inspired by Metronic design
   •	Responsive layouts for all screen sizes
   •	Consistent color scheme and typography
   •	Interactive components with proper feedback
   •	Loading indicators for asynchronous operations
   •	Proper form validation and error messages
   •	Modal dialogs for user interactions

   7.6 Best Practices
   •	Component composition for reusability
   •	Proper code organization and file structure
   •	Consistent naming conventions
   •	Comprehensive error handling
   •	Responsive design principles
   •	Performance optimization
   •	Code maintainability and readability
