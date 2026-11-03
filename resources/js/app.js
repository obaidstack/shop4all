import { HSStaticMethods } from 'preline/non-auto';
import Swal from 'sweetalert2';

document.addEventListener('livewire:navigated', () => {
  HSStaticMethods.autoInit();
});

window.Swal = Swal;