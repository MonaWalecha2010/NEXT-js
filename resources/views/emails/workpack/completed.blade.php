<x-mail::message>
Hi

The Work Pack **{{ $workpack->name }}** for Aeroplane **{{ $aeroplane->name }}** has now been completed.

You can view complete details of this Work Pack by visiting the link to the app below.

<x-mail::button :url="$workpackUrl">
View Work Pack Details
</x-mail::button>


Please click below to download a Work Pack summary report as a PDF.

<x-mail::button :url="$workpackPdf">
Download Report (PDF)
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}

<x-mail::button :url="$loginUrl">
Login
</x-mail::button>

</x-mail::message>
