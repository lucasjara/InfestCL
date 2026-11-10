// Lucide Icons
import { createIcons, icons } from 'lucide';

createIcons({ icons });

// QRCode
import QRCode from 'qrcode';

window.QRCodeGenerator = QRCode;

// SweetAlert2
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// Exponer globalmente para utilizarlos desde Blade
window.Swal = Swal;