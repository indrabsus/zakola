<div>

    <div class="row">

        <div class="container">
          @if(session('sukses'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <h5>Sukses!</h5>
        {{session('sukses')}}
        </div>
        @endif
        @if(session('gagal'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <h5>Gagal!</h5>
        {{session('gagal')}}
        </div>
        @endif
        </div>
        <div class="col">
                <div class="row justify-content-between mt-2">

                    <div class="col-lg-3">
                        <div class="input-group input-group-sm mb-3">
                          <div class="col-3">
                            <select class="form-control" wire:model.live="result">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                            <input type="text" class="form-control" placeholder="Cari..." aria-label="Username" aria-describedby="basic-addon1" wire:model.live="cari">
                            <span class="input-group-text" id="basic-addon1">Cari</span>
                          </div>
                    </div>
                </div>
               {{-- <div class="table-responsive"> --}}
                <table class="table table-stripped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pertanyaan</th>
                            <th>Jawaban</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{$d->pertemuan}}</td>
                            <td class="wrapped-text"><p>{{$d->pertanyaan}}</p></td>
                            <td class="wrapped-text">
                              @php
                                  $ok = App\Models\JwbnRefleksi::where('id_refleksi',$d->id_refleksi)->where('id_user',Auth::user()->id)->first();
                              @endphp
                              {{ $ok == NULL ? '-': $ok->jawaban }}
                            </td>
                            <td>
                              @if ($ok == NULL)
                              <a href="" class="btn btn-success btn-xs" data-bs-toggle="modal" data-bs-target="#cjawab" wire:click='cjawab({{$d->id_refleksi}})'>Jawab</a>
                              @else
                              <button class="btn btn-success btn-sm" disabled>Sudah dijawab</button>
                             @endif

                              </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
               {{-- </div> --}}
                {{$data->links()}}
        </div>
    </div>





    {{-- Edit Modal --}}
    <div class="modal fade" id="cjawab" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Jawab</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">{{ $pertanyaan }}</p>
                <div class="form-group mb-3">
                    <label for="">Jawaban</label>
                    <textarea wire:model="jawaban" cols="30" rows="10" class="form-control"></textarea>
                    <div class="text-danger">
                        @error('jawaban')
                            {{$message}}
                        @enderror
                    </div>
                  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" wire:click='jawab()'>Save changes</button>
            </div>
          </div>
        </div>
      </div>

      <script>
        window.addEventListener('closeModal', event => {
            $('#cjawab').modal('hide');
        })
      </script>

</div>

