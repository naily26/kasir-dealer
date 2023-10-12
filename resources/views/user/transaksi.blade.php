@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Transaksi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item ">Dashboard</li>
            <li class="breadcrumb-item active">Data Transaksi</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<section class="section dashboard">
    <div class="row">
        <div class="card overflow-auto">

            <div class="card-body">
                <h5 class="card-title">Transaksi <span>| Today</span></h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal">
                    Tambah data
                </button>


                <table class="table table-borderless datatable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Jumlah</th>
                            <th>aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>{{$item->nama}}</td>
                            <td>{{$item->nik}}</td>
                            <td>{{$item->jumlah}}</td>
                            <td><a href="#hapusdata{{$item->id}}" data-bs-toggle="modal">hapus</a>
                                <a href="#editdata{{$item->id}}" data-bs-toggle="modal" onclick="changeFunc($item->produk_id);">edit</a>
                            </td>
                        </tr>


                        <div class="modal fade edit" id="editdata{{$item->id}}" tabindex="-1" role="dialog"
                            aria-labelledby="basicModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form method="POST" action="{{route('transaksi.update', $item->id)}}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Produk</label> <br/>
                                                <select id="product_id_edit" class="form-control" name="produk_id" style="width: 100%;" onchange="changeFunc(value);" required>
                                                    <option value="" disabled selected hidden></option>
                                                    @foreach ($produk as $pro)
                                                    <option value="{{$pro->id}}" @if ($pro->id == $item->produk_id) selected  @endif>{{$pro->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Pembeli</label>
                                                <select id="cust_id_edit" class="form-control" name="cust_id" style="width: 100%;" required>
                                                    <option value="" disabled selected hidden></option>
                                                    @foreach ($cust as $cs)
                                                    <option value="{{$cs->id}}" @if ($cs->id == $item->cust_id) selected  @endif>{{$cs->nama}}</option>
                                                    @endforeach
                                                </select>
                                                <p style="font-size:11px;">Jika data pembeli tidak ditemukan, silahkan menambah data baru pada menu <a href="{{route('customer.index')}}">customer</a></p>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Jenis Transaksi</label> <br/>
                                                <div class="form-check form-check-inline" >
                                                    <input class="form-check-input" type="radio" value="cash" name="jenis" id="jenisCash" @if ($item->jenis == 'cash') checked    @endif>
                                                    <label class="form-check-label" for="jenis" required>
                                                      Cash
                                                    </label>
                                                  </div>
                                                  <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" value="credit" name="jenis" id="jenisKredit" @if ($item->jenis == 'credit') checked    @endif>
                                                    <label class="form-check-label" for="jenis" required>
                                                      Credit
                                                    </label>
                                                  </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Biaya Tambahan</label>
                                                <input type="number" name="biaya_tambahan" class="form-control" value="{{$item->biaya_tambahan}}" onchange="addBiaya(value)">
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Jumlah</label>
                                                <input type="number" id="jumlah" name="jumlah" value="{{$item->jumlah}}" class="form-control" readonly>
                                            </div>
                                            <div id="kredit-edit">
                                            <div class="mb-3">
                                                    <label for="exampleFormControlInput1" class="form-label">Uang Muka (Down Payment)</label>
                                                    <input type="number" id="dp" name="dp" value="{{$item->dp}}" class="form-control" onchange="addDp(value)">
                                             </div>
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Lama Kredit</label>
                                                <input type="number" id="lama_kredit" name="lama_kredit" value="{{$item->lama_kredit}}" class="form-control" onchange="addLama(value)">
                                            </div>
                                           
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Angsuran</label>
                                                <input type="number" id="angsuran" name="angsuran" value="{{$item->angsuran}}" class="form-control" readonly>
                                            </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Berkas Pembelian</label>
                                                <input type="file" name="berkas_pembelian" class="form-control" value="{{$item->berkas_pembelian}}" >
                                                <p>Berkas yang diunggah sebelumnya <a href="{{$item->berkas_pembelian}}">dokumen</a></p>
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="hapusdata{{$item->id}}" tabindex="-1" role="dialog"
                            aria-labelledby="basicModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah anda yakin untuk menghapus data ini?</p>
                                    </div>
                                    <form action="{{url('transaksi/'. $item->id)}}" method="post"
                                        enctype="multipart/form-data">
                                        {{ method_field('delete') }}
                                        @csrf
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Yakin</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{route('transaksi.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Produk</label> <br/>
                            <select id="product_id" class="form-control" name="produk_id" style="width: 100%;" onchange="changeFunc(value);" required>
                                <option value="" disabled selected hidden></option>
                                @foreach ($produk as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Pembeli</label>
                            <select id="cust_id" class="form-control" name="cust_id" style="width: 100%;" required>
                                <option value="" disabled selected hidden></option>
                                @foreach ($cust as $item)
                                <option value="{{$item->id}}">{{$item->nama}}</option>
                                @endforeach
                            </select>
                            <p style="font-size:11px;">Jika data pembeli tidak ditemukan, silahkan menambah data baru pada menu <a href="{{route('customer.index')}}">customer</a></p>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Jenis Transaksi</label> <br/>
                            <div class="form-check form-check-inline" >
                                <input class="form-check-input" type="radio" value="cash" name="jenis" id="jenisCash">
                                <label class="form-check-label" for="jenis" required>
                                  Cash
                                </label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="credit" name="jenis" id="jenisKredit">
                                <label class="form-check-label" for="jenis" required>
                                  Credit
                                </label>
                              </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Biaya Tambahan</label>
                            <input type="number" name="biaya_tambahan" class="form-control" onchange="addBiaya(value)">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Jumlah</label>
                            <input type="number" id="jumlah" name="jumlah" class="form-control" readonly>
                        </div>
                        <div id="kredit">
                        <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Uang Muka (Down Payment)</label>
                                <input type="number" id="dp" name="dp" class="form-control" onchange="addDp(value)">
                         </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Lama Kredit</label>
                            <input type="number" id="lama_kredit" name="lama_kredit" class="form-control" onchange="addLama(value)">
                        </div>
                       
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Angsuran</label>
                            <input type="number" id="angsuran" name="angsuran" class="form-control" readonly>
                        </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Berkas Pembelian</label>
                            <input type="file" name="berkas_pembelian" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection


@push('script')

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script>
$("#product_id").select2({
    dropdownParent: $('#basicModal .modal-body'),
    placeholder: 'Select a produk'
});
   $("#cust_id").select2({
    dropdownParent: $('#basicModal .modal-body'),
    placeholder: 'Select a customer'
});
$(document).ready(function(){
    $("#kredit").hide();
    $("#kredit-edit").hide();
});
$('input[type=radio][name=jenis]').change(function() {
    if (this.value == 'credit') {
        $("#kredit").show();
        $("#kredit-edit").show();
        $("#lama_kredit").attr("required", true);
        $("#dp").attr("required", true);
    } else {
        $("#kredit").hide();
        $("#kredit-edit").hide();
        $("#lama_kredit").attr("required", false);
        $("#dp").attr("required", false);
        $('#lama_kredit').val('');  
        $('#dp').val('');
        $('#angsuran').val('');
    }
   
});

$("#product_id_edit").select2({
    dropdownParent: $('.edit .modal-body'),
    placeholder: 'Select a produk'
});
   $("#cust_id_edit").select2({
    dropdownParent: $('.edit .modal-body'),
    placeholder: 'Select a customer'
});

</script>
<script type="text/javascript">
    var harga = 0;
    var biaya = 0;
    var dp = 0;
    var lama = 0;
    function changeFunc(i) {
        var product = @json($produk);
        var getItem = product.find((item) => item.id==i);
        harga = parseFloat(getItem.price);
        setValue();
    }
    function addBiaya(i) {
        biaya = parseFloat(i);
        setValue();
    }
    function setValue(){
        document.getElementById('jumlah').value= (harga+biaya);
    }
    function addDp(i) {
        biaya = parseFloat(i);
        setValueAngsuran()
    }
    function addLama(i) {
        lama = parseFloat(i);
        setValueAngsuran()
    }
    function setValueAngsuran(){
        document.getElementById('angsuran').value= ((harga+biaya-dp)/lama);
    }
    
   </script>

@endpush

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

@endpush
