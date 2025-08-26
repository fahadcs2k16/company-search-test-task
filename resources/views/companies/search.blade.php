@extends('layouts.app')

@section('title', 'Company Search')

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Company Search</h1>
    
    <form method="GET" action="{{ route('companies.search') }}" class="mb-8">
        <div class="flex gap-4">
            <input 
                type="text" 
                name="search" 
                value="{{ $searchTerm }}"
                placeholder="Enter company name..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
            <button 
                type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200"
            >
                Search
            </button>
        </div>
    </form>

    @if($searchTerm && $companies->isNotEmpty())
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                Search Results ({{ $companies->count() }} found)
            </h2>
            
            @foreach($companies as $company)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $company['name'] }}
                            </h3>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><span class="font-medium">Country:</span> {{ $company['country'] }}</p>
                                <p><span class="font-medium">Identifier:</span> {{ $company['identifier'] }}</p>
                                @if(isset($company['state']))
                                    <p><span class="font-medium">State:</span> {{ $company['state'] }}</p>
                                @endif
                                @if(isset($company['company_type']))
                                    <p><span class="font-medium">Type:</span> {{ $company['company_type'] }}</p>
                                @endif
                                <p><span class="font-medium">Address:</span> {{ $company['address'] }}</p>
                            </div>
                        </div>
                        <div class="ml-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $company['country_code'] }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a 
                            href="{{ route('companies.show', [$company['country_code'], $company['id']]) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition duration-200"
                        >
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif($searchTerm)
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg">No companies found for "{{ $searchTerm }}"</p>
            <p class="text-gray-400 mt-2">Try searching with a different term</p>
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg">Enter a company name to search across all countries</p>
        </div>
    @endif
</div>
@endsection