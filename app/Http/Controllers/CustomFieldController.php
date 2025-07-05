<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customFields = Auth::user()->customFields()->ordered()->get();
        return view('custom-fields.index', compact('customFields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('custom-fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'field_type' => 'required|string|in:text,number,email,phone,date,select,textarea,checkbox,url',
            'options' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:500',
            'default_value' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:1',
        ]);

        // Process options if provided
        $options = null;
        if ($validated['field_type'] === 'select' && !empty($validated['options'])) {
            $options = array_map('trim', explode("\n", $validated['options']));
            $options = array_filter($options); // Remove empty lines
        }

        // Generate unique field name from label
        $fieldName = Str::slug($validated['name'], '_');
        $originalName = $fieldName;
        $counter = 1;
        
        // Ensure uniqueness
        while (Auth::user()->customFields()->where('name', $fieldName)->exists()) {
            $fieldName = $originalName . '_' . $counter;
            $counter++;
        }

        // Determine sort order
        $sortOrder = $validated['sort_order'] ?? (Auth::user()->customFields()->max('sort_order') + 1);

        CustomField::create([
            'user_id' => Auth::id(),
            'name' => $fieldName,
            'label' => $validated['name'],
            'type' => $validated['field_type'],
            'options' => $options,
            'is_required' => $request->boolean('is_required'),
            'is_active' => $request->boolean('is_active', true),
            'description' => $validated['description'],
            'default_value' => $validated['default_value'],
            'sort_order' => $sortOrder,
        ]);

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomField $customField)
    {
        // Authorization check
        if ($customField->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('custom-fields.show', compact('customField'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomField $customField)
    {
        // Authorization check
        if ($customField->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('custom-fields.edit', compact('customField'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomField $customField)
    {
        // Authorization check
        if ($customField->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'field_type' => 'required|string|in:text,number,email,phone,date,select,textarea,checkbox,url',
            'options' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:500',
            'default_value' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:1',
        ]);

        // Process options if provided
        $options = null;
        if ($validated['field_type'] === 'select' && !empty($validated['options'])) {
            $options = array_map('trim', explode("\n", $validated['options']));
            $options = array_filter($options); // Remove empty lines
        }

        $customField->update([
            'label' => $validated['name'],
            'type' => $validated['field_type'],
            'options' => $options,
            'is_required' => $request->boolean('is_required'),
            'is_active' => $request->boolean('is_active', true),
            'description' => $validated['description'],
            'default_value' => $validated['default_value'],
            'sort_order' => $validated['sort_order'] ?? $customField->sort_order,
        ]);

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomField $customField)
    {
        // Authorization check
        if ($customField->user_id !== Auth::id()) {
            abort(403);
        }
        
        $customField->delete();

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field deleted successfully.');
    }

    /**
     * Update the sort order of custom fields
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'field_ids' => 'required|array',
            'field_ids.*' => 'exists:custom_fields,id',
        ]);

        foreach ($validated['field_ids'] as $index => $fieldId) {
            $customField = Auth::user()->customFields()->find($fieldId);
            if ($customField) {
                $customField->update(['sort_order' => $index + 1]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle the active status of a custom field
     */
    public function toggleActive(CustomField $customField)
    {
        // Authorization check
        if ($customField->user_id !== Auth::id()) {
            abort(403);
        }
        
        $customField->update(['is_active' => !$customField->is_active]);

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field status updated successfully.');
    }
}
