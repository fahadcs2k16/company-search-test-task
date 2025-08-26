<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Singapore\Company as SingaporeCompany;
use App\Models\Mexico\Company as MexicoCompany;
use App\Models\Philippines\Company as PhilippinesCompany;
use App\Models\Malaysia\Company as MalaysiaCompany;
use Illuminate\Support\Collection;

class CompanySearchService extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $companies = collect();

        if ($searchTerm) {
            $companies = $this->searchAcrossCountries($searchTerm);
        }

        return view('companies.search', compact('companies', 'searchTerm'));
    }

    public function show(string $country, int $id)
    {
        $companyDetails = $this->getCompanyDetails($country, $id);
        
        if (!$companyDetails) {
            abort(404, 'Company not found');
        }

        return view('companies.show', compact('companyDetails'));
    }

    public function searchAcrossCountries(string $searchTerm): Collection
    {
        $results = collect();

        // Search Singapore
        $sgCompanies = SingaporeCompany::where('name', 'like', "%{$searchTerm}%")
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'slug' => $company->slug,
                    'identifier' => $company->registration_number,
                    'country' => 'Singapore',
                    'country_code' => 'SG',
                    'address' => $company->address ?? 'N/A'
                ];
            });

        // Search Mexico
        $mxCompanies = MexicoCompany::where('name', 'like', "%{$searchTerm}%")
            ->with('state')
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'slug' => $company->slug,
                    'identifier' => $company->registration_number,
                    'country' => 'Mexico',
                    'country_code' => 'MX',
                    'address' => $company->address ?? 'N/A',
                    'state' => $company->state->name ?? 'N/A'
                ];
            });

        // Search Philippines
        $phCompanies = PhilippinesCompany::where('name', 'like', "%{$searchTerm}%")
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'slug' => $company->slug,
                    'identifier' => $company->sec_code,
                    'country' => 'Philippines',
                    'country_code' => 'PH',
                    'address' => $company->address ?? 'N/A'
                ];
            });

        // Search Malaysia
        $myCompanies = MalaysiaCompany::where('name', 'like', "%{$searchTerm}%")
            ->with('companyType')
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'slug' => $company->slug,
                    'identifier' => $company->registration_number,
                    'country' => 'Malaysia',
                    'country_code' => 'MY',
                    'address' => $company->address ?? 'N/A',
                    'company_type' => $company->companyType->name ?? 'N/A'
                ];
            });

        return $results->merge($sgCompanies)
                      ->merge($mxCompanies)
                      ->merge($phCompanies)
                      ->merge($myCompanies);
    }

    public function getCompanyDetails(string $country, int $companyId): ?array
    {
        switch (strtoupper($country)) {
            case 'SG':
                return $this->getSingaporeCompanyDetails($companyId);
            case 'MX':
                return $this->getMexicoCompanyDetails($companyId);
            case 'PH':
                return $this->getPhilippinesCompanyDetails($companyId);
            case 'MY':
                return $this->getMalaysiaCompanyDetails($companyId);
            default:
                return null;
        }
    }

    private function getSingaporeCompanyDetails(int $companyId): ?array
    {
        $company = SingaporeCompany::with('reports')->find($companyId);
        
        if (!$company) return null;

        return [
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
                'slug' => $company->slug,
                'identifier' => $company->registration_number,
                'address' => $company->address,
                'country' => 'Singapore',
                'country_code' => 'SG'
            ],
            'reports' => $company->reports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'name' => $report->name,
                    'type' => $report->type,
                    'price' => $report->price,
                    'country_code' => 'SG'
                ];
            })
        ];
    }

    private function getMexicoCompanyDetails(int $companyId): ?array
    {
        $company = MexicoCompany::with(['state', 'availableReports.report'])->find($companyId);
        
        if (!$company) return null;

        return [
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
                'slug' => $company->slug,
                'identifier' => $company->registration_number,
                'address' => $company->address,
                'country' => 'Mexico',
                'country_code' => 'MX',
                'state' => $company->state->name ?? 'N/A'
            ],
            'reports' => $company->availableReports->map(function ($reportState) {
                return [
                    'id' => $reportState->report_id,
                    'name' => $reportState->report->name,
                    'type' => $reportState->report->type,
                    'price' => $reportState->amount,
                    'country_code' => 'MX'
                ];
            })
        ];
    }

    private function getPhilippinesCompanyDetails(int $companyId): ?array
    {
        $company = PhilippinesCompany::with(['reports.reportPrice'])->find($companyId);
        echo "<pre>";
        print_r($company);
        echo "</pre>";

        exit;
        // Group reports by type
        $groupedReports = $company->reports->groupBy('report_type_id');
        
        $reports = collect();
        foreach ($groupedReports as $typeId => $typeReports) {
            $reportType = $typeReports->first()->reportType;
            $periods = $typeReports->pluck('period_date')->map(function ($date) {
                return $date;
            }); 
            if($reportType) {
                $reports->push([
                    'id' => $reportType->id,
                    'name' => $reportType->name,
                    'type' => $reportType->name,
                    'price' => $reportType->price,
                    'periods' => $periods,
                    'country_code' => 'PH'
                ]);
            }
        }

        return [
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
                'slug' => $company->slug,
                'identifier' => $company->sec_code,
                'address' => $company->address,
                'country' => 'Philippines',
                'country_code' => 'PH'
            ],
            'reports' => $reports
        ];
    }

    private function getMalaysiaCompanyDetails(int $companyId): ?array
    {
        $company = MalaysiaCompany::with(['companyType', 'availableReports'])->find($companyId);
        
        if (!$company) return null;

        return [
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
                'slug' => $company->slug,
                'identifier' => $company->registration_number,
                'address' => $company->address,
                'country' => 'Malaysia',
                'country_code' => 'MY',
                'company_type' => $company->companyType->name ?? 'N/A'
            ],
            'reports' => $company->availableReports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'name' => $report->name,
                    'type' => $report->type,
                    'price' => $report->price,
                    'country_code' => 'MY'
                ];
            })
        ];
    }
}
