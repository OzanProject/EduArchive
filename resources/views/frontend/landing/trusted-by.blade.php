<section class="py-12 border-y border-[#e7ebf3] bg-white">
  <div class="max-w-7xl mx-auto px-6 text-center">
    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-8 text-center">Telah digunakan oleh
      instansi di seluruh Indonesia</p>
    <div class="flex flex-wrap justify-center gap-12 opacity-50 grayscale">
      @for($i = 1; $i <= 5; $i++)
        @if(isset($settings['landing_partner_logo_' . $i]))
          <img class="h-8" src="{{ asset($settings['landing_partner_logo_' . $i]) }}" alt="Partner {{ $i }}" />
        @elseif($i == 1)
          <img class="h-8"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAFtQ_2ed8NpFuqKqlhuZKxW8q4zMCz2s6iL_lff6CVLKgyiSsc2qwesZW7Mhtdkjzob8Xa7aH7E3FaCv68A2AZyw07P7vaOoxECmy1_vIqXMaop-3LVeW2xuNi-LWytY-opv6ZjjxY4CIUJO_iILjpINEcVDmngSpSWTviytR0xU2YK9xiutiLYnh6X-ID9nNsYW4tyoPA9VuqypuhcK9iteSUo_euHJpeGjgCNOJrDnMplhpVxZNoV1l5vQpDOqtCAF1v3VSEfVcv" />
        @elseif($i == 2)
          <img class="h-8"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuD_I88y4Vku6hHHG5Bt7QH_Vqr1XWZPyqEV7RqNZrkg0LC8K3jg2Dn79432kKdcZVxBoBNyfPPiKCU_z5TU0CPpFkxYh0N6so0cMZ2yRnCq_wmxlf5qxy0VDcbLjofzAqgJqwSH1wojvagF7HSCTCE6VmUVA9s80-g7gevJg3DtxGR73rIMgrkR9Wo9xqd9Rpdpv4q8evi2BFiG5ycY0B3dfPSIj7v6Bxi8As6F8agB9X-pz1XwdtSiACLmjYoPkTJchF-m4OH4Cwl6" />
        @elseif($i == 3)
          <img class="h-8"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAulya8egPLVnQk3YA0kWjICikJl2OFrWxFTgJHM-x6Hz-KdM2aPLIbRVJyYzID9rYAg9QzqivVfE27JD4jpowVOnzKZ9NqUBSBeBgDEXw5e-y-8V1S-zMo3ccu3Me9Q4tiAcPAXExSitv4bi16efJvsLChr2q968_JlGkk4dBd3PUaFV5Qu_s9HmbI6SPsxqnX37iAHavejq4VaNdDULWy6scVbZyIalMDM_xF4geUYq0fRICFzbwlAPsCqCfx1FL26bKKuwJXXQmG" />
        @endif
      @endfor
    </div>
  </div>
</section>