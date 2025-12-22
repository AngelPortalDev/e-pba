@extends('frontend.master')
@section('content')

<style>
    .sidenav.navbar .navbar-nav .e-men-5 > .nav-link {
    background-color: var(--gk-gray-200);
}
</style>


<main>
    <section class="pt-5 pb-5">
        <div class="container">
            
            <!-- Top Menubar -->
            @include('frontend.institute.layout.institute-common')

            <!-- Content -->

            {{-- <div class="row mt-0 mt-md-4"> --}}

                {{-- Left menubar  --}}
                
                {{-- @include('frontend.teacher.layout.e-mentor-left-menu') --}}

                <div class="col-lg-9 col-md-8 col-12">
                    <!-- Card -->
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header d-flex justify-content-between">
                            <div>
                            <h3 class="mb-0">Teacher Profile Details</h3>
                            <p class="mb-0">You have full control to manage your own account setting.</p>
                            </div>
                            <div>
                            <a href="{{route('institute-teachers')}}" class="btn btn-white bg-blue color-green">Back</a>
                            </div> 
                        </div>
                        <div>
                        </div>
                        <!-- Card body -->
                        <div class="card-body">
                            {{-- @php echo '<pre>'; print_r($instituteData[0]->user['name']); die; @endphp --}}
                            <div>
                               
                                <div class="ms-2">
                                    {{-- <h4 class="mb-0">{{isset($teacherData->lactrure_name) ? $teacherData->lactrure_name : ''}}</h4> --}}

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="university_name">Lecture Name <span class="text-danger">*</span></label>
                                            <input type="text"   class="form-control"
                                                placeholder="University Name"
                                                value="{{ isset($teacherData->lactrure_name) ? $teacherData->lactrure_name : '' }}" readonly>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label" for="university_name">Mobile No <span class="text-danger">*</span></label>
                                            <input type="text"   class="form-control"
                                                placeholder="University Name"
                                                value="{{ isset($teacherData->mobile) ? $teacherData->mobile : '' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="mb-3 col-md-12">
                                            <label for="specialization" class="form-label">
                                                Specialization
                                                <small>(Maximum 50 words)</small>
                                            </label>
                                            <textarea class="form-control" id="specialization" rows="5" name="specialization" placeholder="Write here..." readonly>{{ isset($teacherData->specialization) ? htmlspecialchars_decode($teacherData->specialization) : '' }}</textarea>
        
                                        </div>
                                    </div>
                                    <div class="row">
        
                                        <div class="col-md-12">
                                            <label for="customerNotes" class="form-label">
                                                About Teacher
                                                <small>(Maximum 50 words)</small>
                                            </label> <span class="text-danger">*</span>
                                            <textarea class="form-control" id="about_teacher" rows="5" name="about_teacher" placeholder="Write here..." required="" readonly>{{ isset($teacherData->discription) ? htmlspecialchars_decode($teacherData->discription) : '' }}</textarea>
        
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-12 col-md-6">
                                            <label class="form-label">Resume <span class="text-danger">*</span></label>
                                            @if(isset($teacherData->resume) && !empty($teacherData->resume))
                                                <div class="mb-2">
                                                    <a href="{{ Storage::url($teacherData->resume) }}" target="_blank" class="btn btn-primary">View Resume</a>
                                                </div>
                                            @else
                                                <p>No Resume available</p>
                                            @endif
                                          
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- </div> --}}
        </div>
    </section>
</main>

@endsection
