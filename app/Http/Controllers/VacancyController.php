<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use App\Models\Company;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        // Start query with active vacancies only
        $query = Vacancy::with('company')
            ->active();

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('requirements', 'like', "%{$search}%")
                  ->orWhereHas('company', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply location filter
        if ($request->filled('location') && $request->input('location') !== 'All') {
            $query->where('city', $request->input('location'));
        }

        // Apply employment type filter
        if ($request->filled('employment_type') && $request->input('employment_type') !== 'All') {
            $query->where('employment_type', $request->input('employment_type'));
        }

        // Apply experience level filter
        if ($request->filled('experience_level') && $request->input('experience_level') !== 'All') {
            $query->where('experience_level', $request->input('experience_level'));
        }

        // Apply department filter
        if ($request->filled('department') && $request->input('department') !== 'All') {
            $query->where('department', $request->input('department'));
        }

        // Sort: featured first, then by created date
        $query->orderBy('is_featured', 'desc')
              ->orderBy('created_at', 'desc');

        // Get paginated vacancies
        $vacancies = $query->paginate(12)->appends($request->query());

        // Get unique cities for filter
        $cities = Vacancy::active()
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        // Get unique employment types
        $employmentTypes = Vacancy::active()
            ->whereNotNull('employment_type')
            ->distinct()
            ->pluck('employment_type')
            ->sort()
            ->values();

        // Get unique experience levels
        $experienceLevels = Vacancy::active()
            ->whereNotNull('experience_level')
            ->distinct()
            ->pluck('experience_level')
            ->sort()
            ->values();

        // Get unique departments
        $departments = Vacancy::active()
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->sort()
            ->values();

        return view('vacancies.index', compact('vacancies', 'cities', 'employmentTypes', 'experienceLevels', 'departments'));
    }

    public function show(string $slug)
    {
        $vacancy = Vacancy::with('company')
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Get related vacancies from the same company (only active)
        $relatedVacancies = Vacancy::with('company')
            ->where('company_id', $vacancy->company_id)
            ->where('id', '!=', $vacancy->id)
            ->active()
            ->limit(4)
            ->get();

        return view('vacancies.show', compact('vacancy', 'relatedVacancies'));
    }

    public function apply(Request $request, $vacancyId)
    {
        $vacancy = Vacancy::findOrFail($vacancyId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cv' => 'required|file|mimes:pdf|max:5120', // 5MB max
            'cover_letter' => 'nullable|string|max:5000',
            'phone' => 'nullable|string|max:255',
        ]);

        // Store CV file
        $cvPath = $request->file('cv')->store('job-applications', 'public');

        // Create application
        JobApplication::create([
            'vacancy_id' => $vacancy->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cv_path' => $cvPath,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('vacancies.index')->with('success', 'განაცხადი წარმატებით გაიგზავნა!');
    }
}

