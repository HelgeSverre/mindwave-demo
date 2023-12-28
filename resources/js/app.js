import {
    Alpine,
    Livewire,
} from "../../vendor/livewire/livewire/dist/livewire.esm";
import ToastComponent from "../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts";
import Uploader from "./components/uploader";
import Vapor from "laravel-vapor";

// Alpine Components
Alpine.plugin(ToastComponent);
Alpine.data("Uploader", Uploader);

// Globals
window.Vapor = Vapor;
window.Alpine = Alpine;

// Launch!
Livewire.start();
