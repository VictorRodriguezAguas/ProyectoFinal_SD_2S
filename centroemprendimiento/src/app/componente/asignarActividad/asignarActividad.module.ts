import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { AsignarActividadComponent } from './asignarActividad.component';
import { TreeViewModule } from '@syncfusion/ej2-angular-navigations';
import {ArchwizardModule} from 'angular-archwizard';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { TreeChecklistExampleModule } from 'src/app/paginas/mantenimientos/seguridad/mnt-perfil/TreeChecklistExample/TreeChecklistExample.module';
/**
 * Module
 */
@NgModule({
    imports: [
        CommonModule, FormsModule,TreeViewModule, ArchwizardModule, SharedModule, TreeChecklistExampleModule
    ],
    declarations: [AsignarActividadComponent],
    bootstrap:[AsignarActividadComponent],
    exports: [AsignarActividadComponent]
})
export class AsignarActividadModule {
}
