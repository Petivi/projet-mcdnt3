import { Component, Input } from '@angular/core';

import { FilterDescriptor } from '@progress/kendo-data-query';
import { FilterService, BaseFilterCellComponent } from '@progress/kendo-angular-grid';

import { ComboElement } from '../model/libelle.model';

@Component({
    selector: 'kendo-multifield-filter',
    template: `
        <div class="k-filtercell-wrapper">
            <input kendoTextBox kendoGridFocusable [(ngModel)]="value" (input)="onChange($event.target.value)" />
            <div class="k-filtercell-operator only-clear">
                <button kendoGridFocusable [ngClass]="{'k-clear-button-visible': showButton}" class="k-button k-button-icon"  (click)="onChange()"><span class="k-icon k-i-filter-clear"></span></button>
            </div>
        </div>
    `
})
export class MultiFieldFilterComponent extends BaseFilterCellComponent {
    @Input() public columns: string[];
    @Input() public field: string;
    public value: string;
    public showButton: boolean = false;

    constructor(filterService: FilterService) {
        super(filterService);
    }

    public onChange(value: any = null): void {
        if (this.columns) {
            let allFilters: FilterDescriptor[] = [];
            this.columns.forEach((col: string) => {
                allFilters.push({
                    field: col,
                    operator: "contains",
                    value: value
                });
            });
            let filters = {
                logic: "or",
                filters: allFilters
            };
    
            if (value === null) {
                this.applyFilter(
                    this.removeFilter(this.field)
                );
                this.value = null;
                this.showButton = false;
            } else {
                this.applyFilter(
                    this.updateFilter(<any>filters)
                );
                if (value !== '') {
                    this.showButton = true;
                } else {
                    this.value = null;
                    this.showButton = false;
                }
            }
        }
    }
}

@Component({
    selector: 'kendo-matselect-filter',
    template: `
        <div class="k-filtercell-wrapper input-mat-content">
            <mat-form-field *ngIf="combos" class="input_100">
                <mat-select placeholder="" [multiple]="true" (selectionChange)="onChange($event.value)">
                    <mat-option *ngFor="let combo of combos" [value]="combo.cLibelle">
                        {{combo.cLibelle}}
                    </mat-option>
                </mat-select>
            </mat-form-field>
        </div>
    `
})
export class MatSelectFilterComponent extends BaseFilterCellComponent {
    @Input() public combos: ComboElement[];
    @Input() public field: string;

    constructor(filterService: FilterService) {
        super(filterService);
    }

    public onChange(value: any[] = null): void {
        if (value && value.length > 0) {
            let allFilters: FilterDescriptor[] = [];
            value.forEach((val: string) => {
                allFilters.push({
                    field: this.field,
                    operator: "eq",
                    value: val
                });
            });
            let filters = {
                logic: "or",
                filters: allFilters
            };
            this.applyFilter(
                this.updateFilter(<any>filters)
            );
        } else {
            this.applyFilter(
                this.removeFilter(this.field)
            );
        }
    }
}
