@extends('layouts.app')
@section('content')
<canvas id="chart">
    
</canvas>

<script src="{{ asset('js/stats.js')}}"></script>
@endsection

<body>
<a href="{{ route('host.edit', 73) }}"><h1>EDIT</h1></a>

</body>

</html>
