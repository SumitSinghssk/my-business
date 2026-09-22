{{-- A JSON-LD <script> for one schema.org node (or a list of nodes), usually built by App\Support\StructuredData. --}}

@props([
    'data',
])

<script type="application/ld+json">
    {!! \App\Support\StructuredData::encode($data) !!}
</script>
