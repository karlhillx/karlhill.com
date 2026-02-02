<section id="gallery">
     <div class="sm:mt-20">
         <div class="-my-4 flex justify-center gap-5 overflow-hidden py-4 sm:gap-8">
             @php
                 $images = [
                     [
                         'url' => 'https://earthobservatory.nasa.gov',
                         'src' => '/img/small-earth-observatory.png',
                         'width' => '3936',
                         'height' => '2624',
                         'rotate' => '-rotate-2'
                     ],
                     [
                         'src' => '/img/small-informeddna.png',
                         'width' => '4240',
                         'height' => '2384',
                         'rotate' => '-rotate-2'
                     ],
                     [
                         'src' => '/img/small-direct-readout.png',
                         'width' => '3744',
                         'height' => '5616',
                         'rotate' => 'rotate-2'
                     ],
                     [
                         'src' => '/img/small-flood.png',
                         'width' => '5760',
                         'height' => '3840',
                         'rotate' => 'rotate-2'
                     ],
                     [
                         'src' => '/img/small-mci-verizon.png',
                         'width' => '2400',
                         'height' => '3000',
                         'rotate' => 'rotate-2'
                     ]
                 ];
             @endphp

             @foreach($images as $image)
                 <div class="relative aspect-[9/10] w-44 flex-none overflow-hidden rounded-xl bg-zinc-100 sm:w-72 sm:rounded-2xl {{ $image['rotate'] }}">
                     @if(isset($image['url']))
                         <a href="{{ $image['url'] }}" target="_blank">
                     @endif
                         <img
                             alt=""
                             sizes="(min-width: 640px) 18rem, 11rem"
                             src="{{ $image['src'] }}"
                             width="{{ $image['width'] }}"
                             height="{{ $image['height'] }}"
                             decoding="async"
                             data-nimg="future"
                             class="absolute inset-0 h-full w-full object-cover"
                             loading="lazy"
                             style="color:transparent"
                         >
                     @if(isset($image['url']))
                         </a>
                     @endif
                 </div>
             @endforeach
         </div>
     </div>
</section>
