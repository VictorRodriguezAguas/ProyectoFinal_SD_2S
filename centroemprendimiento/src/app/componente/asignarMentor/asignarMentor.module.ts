import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ArchwizardModule } from 'angular-archwizard';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { AsignarMentorComponent } from './asignarMentor.component';
import { SelectModule } from 'ng-select';
import { NgbPopoverModule, NgbTooltipModule } from '@ng-bootstrap/ng-bootstrap';
import { InputIncrementModule } from '../inputIncrement/inputIncrement.module';
import { SelectMentorModule } from './selectMentor/selectMentor.module';
/**
 * Module
 */
@NgModule({
    imports: [
        CommonModule, FormsModule, ArchwizardModule, 
        SharedModule, SelectModule, NgbTooltipModule, 
        NgbPopoverModule, InputIncrementModule, SelectMentorModule
    ],
    declarations: [AsignarMentorComponent],
    bootstrap: [AsignarMentorComponent],
    exports: [AsignarMentorComponent]
})
export class AsignarMentorModule {
}
