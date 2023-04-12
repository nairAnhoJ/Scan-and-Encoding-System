@extends('layouts.app')

@section('title')
    Document Viewing
@endsection



@section('content')

    {{-- MODAL BUTTON --}}
    <button id="btnViewingModal" class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button" data-modal-toggle="viewingModal">
        Toggle modal
    </button>

    {{-- MAIN MODAL --}}
    <div id="viewingModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 p-10 w-screen md:inset-0 h-screen">
        <div class="relative w-full h-full">
            <!-- Modal content -->
            <div class="relative w-full h-full bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex justify-between items-start px-4 py-2 rounded-t border-b">
                    <h3 class="text-xl font-semibold text-blue-500">
                        Document
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="viewingModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div style="height: calc(100% - 116px);" class="px-6 pt-2.5 space-y-6 pb-3 m-0 flex flex-row">

                    {{-- Left Content --}}
                    <div class="w-2/6 min-h-full h-full">
                        <div style="border: 1px solid #0284c7;" class="h-full p-2 overflow-auto">
                            <div class="w-full">
                                <h1><span class="font-semibold">Date Uploaded: </span><span id="viewDateUploaded"></span></h1>
                                <h1><span class="font-semibold">Department: </span><span id="viewDepartment"></span></h1>
                                <h1><span class="font-semibold">Batch: </span><span id="viewBatch"></span></h1>
                                <h1><span class="font-semibold">Document Type: </span><span id="viewDocType"></span></h1>
                                <h1><span class="font-semibold">Filename: </span><span id="viewFilename"></span></h1>
                                <h1><span class="font-semibold">Uploader: </span><span id="viewUploader"></span></h1>
                            </div>
                            <hr class="my-1">
                            <div id="fileDetails" class="w-full pb-3 pt-1 leading-4">
                            </div>
                        </div>
                    </div>

                    {{-- Right Content --}}
                    <div style="margin: 0px" class="w-4/6 h-full pl-5">
                        <div class="h-full w-full">
                            <embed id="selectedFile" src="" frameborder="0" type="application/pdf" class="h-full w-full">
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center px-6 py-3 space-x-2 rounded-b border-t border-gray-200">
                    <button data-modal-toggle="viewingModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingOverlay" class="inset-0 bg-white fixed flex w-full h-full items-center justify-center duration-300 transition-opacity" style="z-index: 6000">
        <div class="flex-col">
          <div class="w-24 h-24">
            <svg viewBox="0 0 860.1 876.5">
              <path
                class="animate-spin from-blue-500"
                style="stroke: black; stroke-width: 1; transform-origin: 33.47285199% 32.89218482%; animation-duration: 3s; fill: var(--tw-gradient-from)"
                d="M 289.209 146.527 C 251.329 146.527 215.76 161.251 189.083 187.986 C 162.406 214.721 147.624 250.232 147.624 288.112 C 147.624 325.99 162.348 361.387 189.083 388.237 C 215.76 414.914 251.445 429.696 289.209 429.696 C 327.088 429.696 362.657 414.972 389.335 388.237 C 416.011 361.503 430.794 325.99 430.794 288.112 C 430.794 250.232 416.069 214.836 389.335 187.986 C 362.657 161.308 327.088 146.527 289.209 146.527 Z M 289.209 406.022 C 223.902 406.022 171.241 353.129 171.241 288.053 C 171.241 222.977 223.902 170.085 289.209 170.085 C 354.516 170.085 407.177 223.036 407.177 288.053 C 407.177 353.072 354.516 406.022 289.209 406.022 Z M 536.462 229.099 L 514.058 229.099 C 495.753 229.099 480.452 213.739 480.452 194.915 C 480.452 185.503 484.437 177.073 491.538 170.72 L 505.974 156.574 C 520.294 142.427 520.294 119.446 505.974 105.125 L 473.119 72.559 C 466.651 66.091 457.066 62.222 447.308 62.222 C 437.549 62.222 428.137 66.034 421.497 72.559 L 407.639 86.416 C 400.998 93.808 391.874 97.792 382.29 97.792 C 363.408 97.792 347.356 82.432 347.356 64.301 L 347.356 42.013 C 347.356 22.091 331.88 5 311.959 5 L 267.093 5 C 246.768 5 230.542 21.975 230.542 42.013 L 230.542 64.417 C 230.542 82.548 214.894 97.907 196.07 97.907 C 186.658 97.907 178.054 93.923 171.587 86.994 L 157.267 72.847 C 150.799 66.206 141.215 62.511 131.455 62.511 C 121.697 62.511 112.285 66.322 105.645 72.847 L 72.789 105.298 C 58.642 119.446 58.642 142.6 72.789 156.632 L 86.647 170.49 C 94.038 177.13 98.139 185.503 98.139 195.088 C 98.139 213.97 82.779 229.271 64.532 229.271 L 42.128 229.271 C 21.918 229.099 5 245.613 5 265.707 L 5 288.112 L 5 310.516 C 5 330.437 21.976 347.067 42.128 347.067 L 64.532 347.067 C 82.836 347.067 98.139 362.426 98.139 381.25 C 98.139 390.663 94.038 399.381 86.647 406.022 L 72.789 419.591 C 58.642 433.739 58.642 456.72 72.789 470.925 L 105.645 503.664 C 112.112 510.305 121.697 514.001 131.455 514.001 C 141.215 514.001 150.627 510.189 157.267 503.664 L 171.587 489.518 C 177.766 482.589 186.484 478.604 195.896 478.604 C 214.778 478.604 230.369 493.964 230.369 512.095 L 230.369 534.499 C 230.369 554.42 246.595 571.512 266.804 571.512 L 311.612 571.512 C 331.65 571.512 348.337 554.536 348.337 534.499 L 348.337 512.095 C 348.337 493.964 363.812 478.604 382.693 478.604 C 392.106 478.604 400.825 482.704 407.639 489.98 L 421.497 503.838 C 428.137 510.305 437.549 514.173 447.308 514.173 C 457.066 514.173 466.478 510.363 473.119 503.838 L 505.974 471.097 C 520.121 456.951 520.121 433.796 505.974 419.649 L 491.538 405.502 C 484.437 399.15 480.452 390.143 480.452 380.904 C 480.452 362.022 495.811 346.085 514.058 346.085 L 536.462 346.085 C 556.499 346.085 570.819 330.898 570.819 310.862 L 570.819 288.169 L 570.819 265.765 C 570.819 245.613 556.499 229.099 536.462 229.099 Z M 547.549 288.053 L 547.549 310.342 C 547.549 316.521 544.142 322.006 536.809 322.006 L 514.405 322.006 C 499.218 322.006 484.783 328.474 473.87 339.675 C 463.129 350.762 457.182 365.313 457.182 380.673 C 457.182 396.61 463.649 411.334 475.602 422.247 L 489.633 436.106 C 494.484 441.129 494.484 449.386 489.633 454.237 L 456.778 486.977 C 454.41 489.171 451.004 490.499 447.481 490.499 C 443.959 490.499 440.378 489.171 438.185 486.977 L 424.731 473.581 C 413.355 461.628 398.457 454.988 382.693 454.988 C 367.334 454.988 353.187 460.878 342.158 471.502 C 330.956 482.415 325.066 496.735 325.066 512.037 L 325.066 534.441 C 325.066 541.66 318.715 547.837 311.959 547.837 L 267.093 547.837 C 260.337 547.837 254.275 541.66 254.275 534.441 L 254.275 512.037 C 254.275 496.851 248.211 482.415 237.009 471.502 C 225.922 460.878 211.487 454.988 196.3 454.988 C 180.653 454.988 165.639 461.628 154.841 473.292 L 141.156 486.977 C 138.789 489.171 135.382 490.499 131.86 490.499 C 128.337 490.499 124.758 489.344 122.852 487.266 L 122.679 487.092 L 122.506 486.919 L 89.65 454.179 C 84.8 449.329 84.8 441.187 89.65 436.163 L 103.047 422.883 C 115.114 411.68 121.755 396.783 121.755 380.846 C 121.755 365.487 115.865 351.513 105.067 340.426 C 94.154 329.225 79.718 323.45 64.532 323.45 L 41.955 323.45 C 34.564 323.45 28.385 317.098 28.385 310.458 L 28.385 287.881 L 28.385 265.476 C 28.385 258.836 34.564 252.484 41.955 252.484 L 64.359 252.484 C 79.546 252.484 93.981 246.71 104.894 235.508 C 115.634 224.422 121.582 210.159 121.582 194.972 C 121.582 179.036 114.942 164.138 102.873 153.11 L 89.304 139.713 C 86.07 136.48 85.608 132.784 85.608 130.705 C 85.608 128.8 86.07 124.931 89.304 121.698 L 122.043 89.131 C 124.411 86.936 127.818 85.608 131.34 85.608 C 134.862 85.608 138.443 86.763 140.349 88.842 L 140.521 89.015 L 140.695 89.188 L 154.553 103.046 C 165.467 114.826 180.19 121.351 196.012 121.351 C 211.371 121.351 225.634 115.461 236.72 104.837 C 247.922 93.923 254.101 79.603 254.101 64.301 L 254.101 41.897 C 254.101 34.679 259.991 28.501 266.631 28.501 L 311.612 28.501 C 318.253 28.501 323.392 34.679 323.392 41.897 L 323.392 64.301 C 323.392 79.488 329.859 93.923 341.061 104.837 C 352.148 115.461 366.699 121.351 382.059 121.351 C 397.996 121.351 413.008 114.71 424.211 102.757 L 437.607 89.361 C 439.974 87.167 443.381 85.839 446.903 85.839 C 450.426 85.839 454.006 87.167 456.2 89.246 L 489.056 121.813 C 491.423 124.18 492.866 127.414 492.866 130.821 C 492.866 134.228 491.538 137.461 489.171 139.828 L 475.14 153.687 C 463.36 164.6 456.72 179.325 456.72 195.261 C 456.72 210.621 462.609 224.595 473.407 235.681 C 484.321 246.883 498.757 252.657 513.943 252.657 L 536.346 252.657 C 544.315 252.657 547.26 260.048 547.433 265.938 L 547.549 288.053 Z"
              />
              <path
                class="animate-spin from-pink-500"
                style="
                  stroke: black;
                  stroke-width: 1;
                  transform-origin: 74.01464945% 74.46662863%;
                  animation-duration: 4s;
                  animation-direction: reverse;
                  fill: var(--tw-gradient-from);
                "
                d="M 637.588 543.225 C 608.329 543.225 580.855 554.599 560.249 575.25 C 539.643 595.9 528.225 623.33 528.225 652.589 C 528.225 681.846 539.599 709.188 560.249 729.927 C 580.855 750.533 608.419 761.95 637.588 761.95 C 666.847 761.95 694.321 750.578 714.928 729.927 C 735.533 709.277 746.951 681.846 746.951 652.589 C 746.951 623.33 735.578 595.989 714.928 575.25 C 694.321 554.643 666.847 543.225 637.588 543.225 Z M 637.588 743.665 C 587.144 743.665 546.468 702.809 546.468 652.543 C 546.468 602.277 587.144 561.423 637.588 561.423 C 688.032 561.423 728.71 602.323 728.71 652.543 C 728.71 702.765 688.032 743.665 637.588 743.665 Z M 828.571 607.006 L 811.265 607.006 C 797.126 607.006 785.308 595.142 785.308 580.602 C 785.308 573.332 788.386 566.82 793.871 561.913 L 805.021 550.987 C 816.082 540.058 816.082 522.307 805.021 511.245 L 779.643 486.091 C 774.647 481.095 767.244 478.106 759.707 478.106 C 752.169 478.106 744.899 481.051 739.771 486.091 L 729.067 496.794 C 723.937 502.504 716.889 505.581 709.487 505.581 C 694.901 505.581 682.502 493.717 682.502 479.712 L 682.502 462.497 C 682.502 447.109 670.548 433.907 655.161 433.907 L 620.505 433.907 C 604.806 433.907 592.273 447.019 592.273 462.497 L 592.273 479.802 C 592.273 493.807 580.186 505.67 565.646 505.67 C 558.376 505.67 551.73 502.593 546.735 497.241 L 535.674 486.313 C 530.678 481.184 523.275 478.33 515.736 478.33 C 508.199 478.33 500.929 481.273 495.8 486.313 L 470.422 511.379 C 459.494 522.307 459.494 540.192 470.422 551.031 L 481.126 561.736 C 486.835 566.864 490.002 573.332 490.002 580.735 C 490.002 595.32 478.138 607.139 464.044 607.139 L 446.739 607.139 C 431.128 607.006 418.06 619.762 418.06 635.283 L 418.06 652.589 L 418.06 669.894 C 418.06 685.281 431.173 698.127 446.739 698.127 L 464.044 698.127 C 478.182 698.127 490.002 709.99 490.002 724.53 C 490.002 731.801 486.835 738.535 481.126 743.665 L 470.422 754.145 C 459.494 765.073 459.494 782.824 470.422 793.796 L 495.8 819.084 C 500.795 824.214 508.199 827.068 515.736 827.068 C 523.275 827.068 530.545 824.124 535.674 819.084 L 546.735 808.157 C 551.508 802.805 558.242 799.727 565.512 799.727 C 580.096 799.727 592.139 811.591 592.139 825.596 L 592.139 842.902 C 592.139 858.29 604.672 871.492 620.282 871.492 L 654.893 871.492 C 670.37 871.492 683.26 858.379 683.26 842.902 L 683.26 825.596 C 683.26 811.591 695.213 799.727 709.798 799.727 C 717.069 799.727 723.803 802.894 729.067 808.514 L 739.771 819.218 C 744.899 824.214 752.169 827.201 759.707 827.201 C 767.244 827.201 774.514 824.258 779.643 819.218 L 805.021 793.929 C 815.949 783.002 815.949 765.117 805.021 754.19 L 793.871 743.263 C 788.386 738.356 785.308 731.399 785.308 724.263 C 785.308 709.678 797.171 697.368 811.265 697.368 L 828.571 697.368 C 844.048 697.368 855.109 685.637 855.109 670.161 L 855.109 652.633 L 855.109 635.328 C 855.109 619.762 844.048 607.006 828.571 607.006 Z M 837.134 652.543 L 837.134 669.76 C 837.134 674.532 834.503 678.769 828.839 678.769 L 811.533 678.769 C 799.803 678.769 788.653 683.765 780.224 692.417 C 771.927 700.981 767.333 712.22 767.333 724.085 C 767.333 736.395 772.329 747.768 781.561 756.197 L 792.399 766.901 C 796.146 770.781 796.146 777.159 792.399 780.906 L 767.021 806.195 C 765.192 807.889 762.561 808.915 759.84 808.915 C 757.12 808.915 754.354 807.889 752.66 806.195 L 742.268 795.847 C 733.482 786.615 721.974 781.486 709.798 781.486 C 697.933 781.486 687.006 786.035 678.487 794.241 C 669.834 802.671 665.285 813.732 665.285 825.551 L 665.285 842.858 C 665.285 848.434 660.379 853.205 655.161 853.205 L 620.505 853.205 C 615.287 853.205 610.605 848.434 610.605 842.858 L 610.605 825.551 C 610.605 813.821 605.921 802.671 597.268 794.241 C 588.704 786.035 577.554 781.486 565.824 781.486 C 553.738 781.486 542.141 786.615 533.8 795.624 L 523.229 806.195 C 521.401 807.889 518.77 808.915 516.049 808.915 C 513.328 808.915 510.563 808.023 509.091 806.418 L 508.958 806.283 L 508.824 806.15 L 483.445 780.861 C 479.699 777.115 479.699 770.826 483.445 766.945 L 493.793 756.688 C 503.114 748.035 508.244 736.528 508.244 724.218 C 508.244 712.355 503.694 701.561 495.354 692.997 C 486.924 684.345 475.774 679.884 464.044 679.884 L 446.605 679.884 C 440.896 679.884 436.123 674.978 436.123 669.849 L 436.123 652.41 L 436.123 635.104 C 436.123 629.976 440.896 625.069 446.605 625.069 L 463.91 625.069 C 475.641 625.069 486.791 620.609 495.22 611.957 C 503.516 603.394 508.11 592.377 508.11 580.646 C 508.11 568.337 502.981 556.829 493.659 548.31 L 483.178 537.962 C 480.68 535.465 480.323 532.61 480.323 531.004 C 480.323 529.532 480.68 526.544 483.178 524.047 L 508.466 498.891 C 510.295 497.196 512.927 496.17 515.647 496.17 C 518.368 496.17 521.134 497.062 522.606 498.668 L 522.739 498.802 L 522.873 498.935 L 533.578 509.64 C 542.008 518.739 553.38 523.779 565.601 523.779 C 577.465 523.779 588.482 519.229 597.045 511.023 C 605.697 502.593 610.47 491.532 610.47 479.712 L 610.47 462.407 C 610.47 456.832 615.02 452.06 620.149 452.06 L 654.893 452.06 C 660.022 452.06 663.992 456.832 663.992 462.407 L 663.992 479.712 C 663.992 491.443 668.987 502.593 677.64 511.023 C 686.203 519.229 697.443 523.779 709.308 523.779 C 721.618 523.779 733.214 518.649 741.866 509.416 L 752.213 499.069 C 754.042 497.374 756.673 496.349 759.394 496.349 C 762.115 496.349 764.88 497.374 766.575 498.98 L 791.953 524.136 C 793.782 525.964 794.896 528.462 794.896 531.094 C 794.896 533.725 793.871 536.222 792.042 538.051 L 781.204 548.756 C 772.105 557.186 766.977 568.56 766.977 580.869 C 766.977 592.733 771.525 603.527 779.866 612.09 C 788.296 620.743 799.447 625.203 811.177 625.203 L 828.481 625.203 C 834.636 625.203 836.911 630.912 837.045 635.461 L 837.134 652.543 Z"
              />
              <path
                class="animate-spin from-yellow-500"
                style="
                  stroke: black;
                  stroke-width: 1;
                  transform-origin: 78.02581095% 16.12093553%;
                  animation-duration: 3.5s;
                  animation-direction: reverse;
                  fill: var(--tw-gradient-from);
                "
                d="M 671.717 76.053 C 654.289 76.053 637.926 82.828 625.652 95.128 C 613.378 107.428 606.577 123.766 606.577 141.194 C 606.577 158.62 613.352 174.906 625.652 187.258 C 637.926 199.532 654.343 206.333 671.717 206.333 C 689.145 206.333 705.51 199.559 717.783 187.258 C 730.056 174.959 736.857 158.62 736.857 141.194 C 736.857 123.766 730.083 107.481 717.783 95.128 C 705.51 82.854 689.145 76.053 671.717 76.053 Z M 671.717 195.441 C 641.671 195.441 617.444 171.106 617.444 141.166 C 617.444 111.226 641.671 86.892 671.717 86.892 C 701.763 86.892 725.992 111.253 725.992 141.166 C 725.992 171.08 701.763 195.441 671.717 195.441 Z M 785.473 114.043 L 775.164 114.043 C 766.743 114.043 759.704 106.976 759.704 98.316 C 759.704 93.986 761.537 90.107 764.804 87.184 L 771.446 80.676 C 778.034 74.167 778.034 63.594 771.446 57.005 L 756.33 42.022 C 753.354 39.047 748.945 37.267 744.455 37.267 C 739.965 37.267 735.635 39.021 732.581 42.022 L 726.205 48.397 C 723.15 51.798 718.952 53.631 714.542 53.631 C 705.855 53.631 698.469 46.565 698.469 38.223 L 698.469 27.969 C 698.469 18.803 691.35 10.94 682.185 10.94 L 661.542 10.94 C 652.192 10.94 644.727 18.749 644.727 27.969 L 644.727 38.277 C 644.727 46.618 637.527 53.684 628.867 53.684 C 624.536 53.684 620.578 51.851 617.602 48.663 L 611.014 42.154 C 608.039 39.1 603.629 37.4 599.138 37.4 C 594.65 37.4 590.319 39.153 587.264 42.154 L 572.149 57.084 C 565.639 63.594 565.639 74.247 572.149 80.703 L 578.523 87.079 C 581.924 90.133 583.811 93.986 583.811 98.395 C 583.811 107.082 576.744 114.122 568.349 114.122 L 558.042 114.122 C 548.744 114.043 540.96 121.641 540.96 130.886 L 540.96 141.194 L 540.96 151.501 C 540.96 160.666 548.771 168.318 558.042 168.318 L 568.349 168.318 C 576.77 168.318 583.811 175.383 583.811 184.044 C 583.811 188.375 581.924 192.386 578.523 195.441 L 572.149 201.684 C 565.639 208.193 565.639 218.766 572.149 225.301 L 587.264 240.364 C 590.239 243.419 594.65 245.119 599.138 245.119 C 603.629 245.119 607.96 243.365 611.014 240.364 L 617.602 233.855 C 620.446 230.668 624.456 228.834 628.787 228.834 C 637.473 228.834 644.647 235.901 644.647 244.242 L 644.647 254.55 C 644.647 263.715 652.112 271.579 661.41 271.579 L 682.025 271.579 C 691.244 271.579 698.921 263.768 698.921 254.55 L 698.921 244.242 C 698.921 235.901 706.04 228.834 714.728 228.834 C 719.059 228.834 723.07 230.721 726.205 234.068 L 732.581 240.443 C 735.635 243.419 739.965 245.198 744.455 245.198 C 748.945 245.198 753.275 243.445 756.33 240.443 L 771.446 225.38 C 777.955 218.872 777.955 208.22 771.446 201.71 L 764.804 195.202 C 761.537 192.279 759.704 188.135 759.704 183.884 C 759.704 175.198 766.77 167.865 775.164 167.865 L 785.473 167.865 C 794.692 167.865 801.28 160.878 801.28 151.66 L 801.28 141.22 L 801.28 130.913 C 801.28 121.641 794.692 114.043 785.473 114.043 Z M 790.573 141.166 L 790.573 151.421 C 790.573 154.264 789.006 156.787 785.632 156.787 L 775.324 156.787 C 768.337 156.787 761.696 159.763 756.676 164.917 C 751.734 170.018 748.997 176.712 748.997 183.779 C 748.997 191.111 751.973 197.885 757.472 202.905 L 763.928 209.282 C 766.159 211.593 766.159 215.392 763.928 217.624 L 748.812 232.687 C 747.722 233.695 746.155 234.307 744.535 234.307 C 742.914 234.307 741.267 233.695 740.257 232.687 L 734.068 226.523 C 728.835 221.024 721.98 217.969 714.728 217.969 C 707.66 217.969 701.152 220.679 696.078 225.566 C 690.924 230.587 688.215 237.176 688.215 244.215 L 688.215 254.523 C 688.215 257.845 685.292 260.687 682.185 260.687 L 661.542 260.687 C 658.434 260.687 655.646 257.845 655.646 254.523 L 655.646 244.215 C 655.646 237.229 652.855 230.587 647.701 225.566 C 642.6 220.679 635.959 217.969 628.973 217.969 C 621.774 217.969 614.866 221.024 609.898 226.39 L 603.602 232.687 C 602.513 233.695 600.946 234.307 599.325 234.307 C 597.704 234.307 596.057 233.775 595.18 232.819 L 595.101 232.739 L 595.021 232.66 L 579.905 217.597 C 577.674 215.366 577.674 211.62 579.905 209.308 L 586.068 203.199 C 591.62 198.044 594.676 191.19 594.676 183.858 C 594.676 176.792 591.966 170.363 586.998 165.262 C 581.977 160.109 575.336 157.452 568.349 157.452 L 557.962 157.452 C 554.562 157.452 551.718 154.529 551.718 151.474 L 551.718 141.087 L 551.718 130.779 C 551.718 127.725 554.562 124.802 557.962 124.802 L 568.269 124.802 C 575.256 124.802 581.898 122.145 586.918 116.992 C 591.86 111.892 594.597 105.329 594.597 98.342 C 594.597 91.011 591.541 84.156 585.989 79.082 L 579.746 72.918 C 578.258 71.431 578.046 69.73 578.046 68.774 C 578.046 67.897 578.258 66.117 579.746 64.63 L 594.808 49.646 C 595.898 48.637 597.465 48.026 599.085 48.026 C 600.706 48.026 602.354 48.557 603.231 49.513 L 603.31 49.593 L 603.389 49.673 L 609.766 56.049 C 614.787 61.469 621.56 64.47 628.84 64.47 C 635.906 64.47 642.468 61.76 647.568 56.873 C 652.722 51.851 655.565 45.263 655.565 38.223 L 655.565 27.915 C 655.565 24.594 658.275 21.753 661.33 21.753 L 682.025 21.753 C 685.079 21.753 687.444 24.594 687.444 27.915 L 687.444 38.223 C 687.444 45.21 690.42 51.851 695.574 56.873 C 700.674 61.76 707.369 64.47 714.436 64.47 C 721.768 64.47 728.675 61.415 733.829 55.915 L 739.991 49.753 C 741.08 48.742 742.648 48.133 744.268 48.133 C 745.889 48.133 747.536 48.742 748.546 49.699 L 763.662 64.683 C 764.751 65.772 765.415 67.26 765.415 68.827 C 765.415 70.394 764.804 71.882 763.715 72.971 L 757.26 79.348 C 751.839 84.369 748.786 91.143 748.786 98.475 C 748.786 105.541 751.494 111.971 756.463 117.071 C 761.484 122.225 768.126 124.882 775.112 124.882 L 785.419 124.882 C 789.085 124.882 790.441 128.282 790.52 130.992 L 790.573 141.166 Z"
              />
            </svg>
          </div>
          <div class="mt-3 text-gray-900 font-mono text-sm sm:text-xs">Loading...</div>
        </div>
    </div>


    <div class="p-5 h-full">
        <h1 class="text-sky-600 text-xl font-bold mb-3 text-center">Documents Viewing</h1>
        {{-- <form id="frmGenerate" method="POST" action="{{ route('report.generate') }}" enctype="multipart/form-data" class="flex h-24 items-center">
            @csrf
            <div class="w-full h-24 grid grid-cols-4 grid-rows-2 gap-x-3 text-center">
                <div class="self-center">
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                          <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input datepicker type="text" name="startDate" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-1.5" placeholder="Select Start Date" value="{{ $dateStart }}">
                    </div>
                </div>
                <div class="self-center">
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                          <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                        </div>
                        <input datepicker type="text" name="endDate" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-1.5" placeholder="Select End Date" value="{{ $dateEnd }}">
                    </div>
                </div>
                <div class="self-center">
                    <div class="flex w-full">
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-32 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
                            Department
                        </div>
                        <label for="batch" class="sr-only">Choose a Department</label>
                        <select {{ $user->role == '0' ? 'disabled' : '' }} id="department" name="department" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg border-l-gray-100 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5">
                            <option value="0">All</option>
                            @foreach ($depts as $dept)
                                <option {{ ($user->role == '0') ? (($user->department == $dept->id) ? 'selected' : '') : '' }} value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <div class="flex items-center mr-4">
                        <input {{ ($encodedCB == 1) ? 'checked' : '' }} id="encodedCB" name="encodedCB" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="encodedCB" class="ml-1 text-sm font-medium text-gray-900">Encoded</label>
                    </div>
                    <div class="flex items-center mr-4">
                        <input {{ ($checkedCB == 1) ? 'checked' : '' }} id="checkedCB" name="checkedCB" type="checkbox" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="checkedCB" class="ml-1 text-sm font-medium text-gray-900">Checked</label>
                    </div>
                </div>
                <div class="self-center">
                    <div class="flex w-full">
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-32 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
                            Batch
                        </div>
                        <label for="batch" class="sr-only">Choose a Batch</label>
                        <select id="batch" name="batch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg border-l-gray-100 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5">
                                <option value="0">All</option>
                                @foreach ($sbatches as $sbatch)
                                    <option value="{{ $sbatch->id }}" @if($batchID == $sbatch->id) selected @endif>{{ $sbatch->name }}</option>
                                @endforeach
                        </select>
                    </div>
                </div>
                <div class="self-center">
                    <div class="flex w-full">
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-32 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
                            Doc Type
                        </div>
                        <label for="docType" class="sr-only">Choose a Document Type</label>
                        <select id="docType" name="docType" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg border-l-gray-100 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5">
                            <option value="0">All</option>
                            @foreach ($docTypes as $docType)
                                <option value="{{ $docType->id }}" @if($docTypeID == $docType->id) selected @endif>{{ $docType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="self-center">
                    <div class="flex w-full">
                        <div id="states-button" class="cursor-default inline-flex justify-center items-center py-1.5 px-2 w-32 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg">
                            Uploader
                        </div>
                        <label for="user" class="sr-only">Choose a Uploader</label>
                        <select id="user" name="user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg border-l-gray-100 border-l-2 focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5">
                            <option value="0">All</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if($userID == $user->id) selected @endif>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="self-center">
                    <button type="submit" id="btnGenerate" class="self-center h-8 w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-lg px-5 py-px focus:outline-none">Generate</button>
                </div>
            </div>
        </form> --}}


        <div class="flex flex-row-reverse">
            <div class="w-2/5">
                <form method="GET" action="" id="searchForm" class="w-full">
                    <label for="searchInput" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="search" id="searchInput" class="block z-10 w-full px-4 py-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="SEARCH" value="{{ $search }}" autocomplete="off">
                        <button id="clearButton" type="button" class=" absolute right-20 bottom-1">
                            <i class="uil uil-times text-2xl"></i>
                        </button>
                        <button id="searchButton" type="button" style="bottom: 5px; right: 5px;" type="submit" class="text-white absolute bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2.5 py-1.5">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="w-full mt-3">
            <div id="generateResult" class="w-full grid grid-cols-3 text-center">
                <div><span class="tracking-wide">Total Uploaded: </span><span class="font-bold tracking-wide">{{ $uploadCount }}</span></div>
                <div><span class="tracking-wide">Total Encoded: </span><span class="font-bold tracking-wide">{{ $EncodeCount }}</span></div>
                <div><span class="tracking-wide">Total Checked: </span><span class="font-bold tracking-wide">{{ $CheckedCount }}</span></div>
            </div>
            <hr class="my-3">

            <div>
                <div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Action
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Department
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Batch
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Document Type
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        File Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Date Uploaded
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $x = 1;
                                @endphp
                                @foreach ($documents as $document)
                                    <tr class=" {{ ($x++ % 2 == 0)? 'bg-gray-50' : 'bg-white'; }} border-b">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
                                            <button data-id="{{$document->id}}" class="viewButton text-blue-500 font-bold tracking-wide">View</button>
                                        </th>
                                        <td>{{ $document->department }}</td>
                                        <td>{{ $document->batch }}</td>
                                        <td>{{ $document->docType }}</td>
                                        <td>{{ $document->name }}</td>
                                        <td>
                                            @php
                                                if($document->is_Encoded == '1' && $document->is_Checked == '0'){
                                                    echo 'ENCODED';
                                                }elseif($document->is_Encoded == '1' && $document->is_Checked == '1'){
                                                    echo 'CHECKED';
                                                }elseif($document->is_Encoded == '0' && $document->is_Checked == '0'){
                                                    echo 'UPLOADED';
                                                }
                                            @endphp
                                        </td>
                                        <td>{{ $document->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- PAGINATION --}}
                <div class="grid md:grid-cols-2 mt-3 px-3">
                    @php
                        $prev = $page - 1;
                        $next = $page + 1;
                        $from = ($prev * 100) + 1;
                        $to = $page * 100;
                        if($to > $documentCounts){
                            $to = $documentCounts;
                        }if($documentCounts == 0){
                            $from = 0;
                        }
                    @endphp
                    <div class="justify-self-center md:justify-self-start self-center">
                        <span class="text-sm text-gray-700">
                            Showing <span class="font-semibold text-gray-900">{{ $from }}</span> to <span class="font-semibold text-gray-900">{{ $to }}</span> of <span class="font-semibold text-gray-900">{{ $documentCounts }}</span> Items
                        </span>
                    </div>

                    <div class="justify-self-center md:justify-self-end">
                        <nav aria-label="Page navigation example" class="h-8 mb-0.5 shadow-xl">
                            <ul class="inline-flex items-center -space-x-px">
                                <li>
                                    <a href="{{ ($search == '') ? url('/reports/'.$prev) : url('/reports/'.$prev.'/'.$search);  }}"  class="{{ ($page == 1) ? 'pointer-events-none' : ''; }} block w-9 h-9 leading-9 text-center text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">
                                        <i class="uil uil-angle-left-b"></i>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li>
                                    <p class="block w-9 h-9 leading-9 text-center z-10 text-gray-500 border border-gray-300 bg-white font-semibold">{{ $page }}</p>
                                </li>
                                <li>
                                    <a href="{{ ($search == '') ? url('/reports/'.$next) : url('/reports/'.$next.'/'.$search); }}" class="{{ ($to == $documentCounts) ? 'pointer-events-none' : ''; }} block w-9 h-9 leading-9 text-center text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">
                                        <i class="uil uil-angle-right-b"></i>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            {{-- PAGINATION END --}}

            {{-- <div class="pb-5">
                <table id="table_id" class="stripe hover nowrap row-border dt-body-center" style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Department</th>
                            <th>Batch</th>
                            <th>Document Type</th>
                            <th>File Name</th>
                            <th>Status</th>
                            <th>Date Uploaded</th>
                            <th>Uploader</th>

                            @for ($i = 1; $i <= 15; $i++)
                                <th class="hidden">index</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="text-sm">
                        @php
                            $x = 0;
                        @endphp
                        @if (isset($documents))
                            @foreach ($documents as $document)
                                <tr>
                                    <td><button {{ ($document->is_Checked == 0 ? 'disabled' : '') }} data-id="{{ $document->id }}" class="btnView disabled:text-neutral-500 disabled:pointer-events-none text-blue-500 font-bold">View</button></td>
                                    <td>{{ $document->department }}</td>
                                    <td>{{ $document->batch }}</td>
                                    <td>{{ $document->docType }}</td>
                                    <td>{{ $document->name }}</td>
                                    <td>
                                        @php
                                            if($document->is_Encoded == '1' && $document->is_Checked == '0'){
                                                echo 'ENCODED';
                                            }elseif($document->is_Encoded == '1' && $document->is_Checked == '1'){
                                                echo 'CHECKED';
                                            }elseif($document->is_Encoded == '0' && $document->is_Checked == '0'){
                                                echo 'UPLOADED';
                                            }
                                        @endphp
                                    </td>
                                    <td>{{ $document->created_at }}</td>
                                    <td>{{ $document->uploader }}</td>
                                    
                                    @for ($i = 1; $i <= 15; $i++)
                                        @php
                                            $colName = "field{$i}";
                                        @endphp
                                        <td class="hidden">{{ $document->$colName }}</td>
                                    @endfor
                                </tr>
                                @php
                                    $x++;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div> --}}
        </div>
    </div>



    <script type="text/javascript">
        $(document).ready( function () {
            $('#loadingOverlay').addClass('hidden');

            $('#searchButton').click(function(){
                var search = $('#searchInput').val();
                if(search != ""){
                    $('#searchForm').prop('action', `{{ url('/reports/1/${search}') }}`);
                }else{
                    $('#searchForm').prop('action', `{{ url('/reports/1') }}`);
                }
                $('#searchForm').submit();
            });

            $('#searchInput').on('keydown', function(event) {
                if (event.keyCode === 13) {
                    $('#searchButton').click();
                    event.preventDefault();
                }
            });

            $('#clearButton').click(function(){
                $('#searchInput').val('');
            });

            jQuery(document).on( "click", ".viewButton", function(){
                var docID = $(this).data('id');
                alert(docID);
                // var _token = $('input[name="_token"]').val();

                // $.ajax({
                //     url: "{{ route('report.view') }}",
                //     method: "POST",
                //     dataType: 'json',
                //     data: {
                //         docID: docID,
                //         _token: _token
                //     },
                //     success:function(res){
                //         alert(res.id);
                //         $('#viewDateUploaded').html(res.DateUploadedOut);
                //         $('#viewDepartment').html(res.DepartmentOut);
                //         $('#viewBatch').html(res.BatchOut);
                //         $('#viewDocType').html(res.DocTypeOut);
                //         $('#viewFilename').html(res.FilenameOut);
                //         $('#viewUploader').html(res.UploaderOut);
                //         $('#selectedFile').prop('src', res.FileSrcOut);
                //         $('#fileDetails').html(res.fileDetails);
                //         $('#btnViewingModal').click();
                //     }
                // })
            });
            // $('#table_id').DataTable();

            // $(document).on('click', '#btnGenerate', function(){
            //     $('#user').prop('disabled', false);
            //     $('#docType').prop('disabled', false);
            //     $('#batch').prop('disabled', false);
            // });

            // $('#department').change(function(){
            //     var dept = $('#department option:selected').val();
            //     var _token = $('input[name="_token"]').val();
                    
            //     $.ajax({
            //         url: "",
            //         method: "POST",
            //         dataType: 'json',
            //         data: {
            //             dept: dept,
            //             _token: _token
            //         },
            //         success:function(res){
            //             $('#batch').html(res.batchOut);
            //             $('#docType').html(res.docTypeOut);
            //             $('#user').html(res.userOut);
            //         }
            //     })
            // });
        } );
    </script>
@endsection