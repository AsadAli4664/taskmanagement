@extends('portal.layout.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Task</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('task.index')}}">Police Record</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card card-info card-outline">
        {{-- <div class="card-header">
               <h4>General Information</h4>
            </div> --}}
        <div class="card-body">
            <form action="{{route('task.update', $task->id)}}" method="POST">
                {{csrf_field()}}
        

                    <div class="form-group row">
                            <div class="col-sm-9">
                            <input type="text"  value="{{old('crime_no') ?? $task->crime_no}}" class="form-control" id="crime_no" name="crime_no" dir="rtl" placeholder="00/00" {{ old('crime_no') }} required>
                            </div>
                            <label for="crime_no" class="col-sm-3 col-form-label required">مقدمہ معہ تاریخ</label>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-9">
                            <input type="text" value="{{old('crime_section') ?? $task->crime_section}}" class="form-control" id="crime_section" name="crime_section" placeholder="379 PPC" dir="rtl" required>
                            </div>
                            <label for="crime_section" class="col-sm-3 col-form-label required">جرم</label>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-9">
                            <textarea class="form-control @error('criminal_address') is-invalid @enderror" dir="rtl" rows="3" name="criminal_address" id="txtBox2" placeholder="نام و پتہ ملزمان" required>{{old('criminal_address') ?? $task->criminal_address}}</textarea>
                            </div>
                            <label for="criminal_address" class="col-sm-3 col-form-label required">  نام و پتہ ملزمان</label>
                        </div>
                  
                        <div class="form-group row">
                            <div class="col-sm-9">
                            <input type="date" value="{{old('arrest_date') ?? $task->arrest_date}}" class="form-control" id="arrest_date"  name="arrest_date" dir="rtl" placeholder="date" required>
                            </div>
                            <label for="arrest_date" class="col-sm-3 col-form-label required">تاریخ گرفتاری  </label>
                        </div>
                        <div class="form-group row">
                        <div class="col-sm-5">
                        <select name="days" dir="rtl" class="form-control" required>
                                <option value="دن" {{ (old('title') ?? $task->title) == 'دن' ? 'selected' : '' }}>دن</option>
                                <option value="یوم" {{ (old('title') ?? $task->title) == 'یوم' ? 'selected' : '' }}>یوم</option>
                                <option value="ہفتہ" {{ (old('title') ?? $task->title) == 'ہفتہ' ? 'selected' : '' }}>ہفتہ</option>
                                <option value="مہینہ" {{ (old('title') ?? $task->title) == 'مہینہ' ? 'selected' : '' }}>مہینہ</option>
                                <option value="سال" {{ (old('title') ?? $task->title) == 'سال' ? 'selected' : '' }}>سال</option>
                            </select>
                            
                            </div>
                            <div class="col-sm-4">
                            <input type="text"  value="{{old('remand') ?? $task->remand}}" class="form-control" id="remand" name="remand" placeholder="2" dir="rtl" required>
                            </div>
                          
                            <label for="remand" class="col-sm-3 col-form-label required">جسمانی ریمانڈ  </label>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-9">
                            <input type="text" value="{{old('arrest_status') ?? $task->arrest_status}}" class="form-control" id="arrest_status" name="arrest_status" placeholder="گرفتار" dir="rtl" required>
                            </div>
                            <label for="arrest_status" class="col-sm-3 col-form-label required">پوزیشن ملزمان</label>
                        </div>
                        <div class="form-group row">
                        <div class="col-sm-4">
                          
                        <select name="designation" class="form-control" required>
                                    <option value="">عہدہ</option>
                                    <option value="ASI" {{ (old('designation') ?? $task->designation) == 'ASI' ? 'selected' : '' }}>ASI</option>
                                    <option value="TASI" {{ (old('designation') ?? $task->designation) == 'TASI' ? 'selected' : '' }}>TASI</option>
                                    <option value="SI" {{ (old('designation') ?? $task->designation) == 'SI' ? 'selected' : '' }}>SI</option>
                                    <option value="TSI" {{ (old('designation') ?? $task->designation) == 'TSI' ? 'selected' : '' }}>TSI</option>
                                    <option value="Inspector" {{ (old('designation') ?? $task->designation) == 'Inspector' ? 'selected' : '' }}>Inspector</option>
                                    <option value="DSP" {{ (old('designation') ?? $task->designation) == 'DSP' ? 'selected' : '' }}>DSP</option>
                                </select>

                            </div>
                            <div class="col-sm-5">
                            <input type="text" value="{{old('arrest_by') ?? $task->arrest_by}}" class="form-control" id="arrest_by" name="arrest_by" placeholder="آفیسر کا نام" dir="rtl" required>
                            </div>
                           
                            <label for="arrest_by" class="col-sm-3 col-form-label required">آفیسر گرفتار کنندہ </label>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-9">
                            <input type="text" value="{{old('condition') ?? $task->condition}}"  class="form-control" id="condition" name="condition" placeholder="کیفیت" dir="rtl" required>
                            </div>
                            <label for="condition" class="col-sm-3 col-form-label required"> کیفیت</label>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary my-2">Update Record</button>
                            </div>
                        </div>
                
            </form>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <div class="d-flex justify-content-center">
            </div>
        </div>
    </div>
    <!-- /.card -->

</section>
<script>

    window.onload = function() {
        MakeTextBoxUrduEnabled(document.getElementById('txtBox2'));//enable Urdu in textarea
        MakeTextBoxUrduEnabled(document.getElementById('arrest_status'));//enable Urdu in textarea
        MakeTextBoxUrduEnabled(document.getElementById('arrest_by'));//enable Urdu in textarea
        MakeTextBoxUrduEnabled(document.getElementById('condition'));//enable Urdu in textarea
        MakeTextBoxUrduEnabled(document.getElementById('inputBox'));//enable Urdu in input field
    };

</script>
@endsection