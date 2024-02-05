import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup } from '@angular/forms';

@Component({
  selector: 'app-adherent',
  templateUrl: './adherent.page.html',
})
export class AdherentPage implements OnInit {
  form = new FormGroup({
    email: new FormControl(''),
    firstName: new FormControl(''),
    lastName: new FormControl(''),
    address: new FormControl(''),
  });

  fileName: string = localStorage.getItem('fileName') || '';
  cni: string = localStorage.getItem('cni') || '';

  ngOnInit() {
    const formData = JSON.parse(localStorage.getItem('formData') || '{}');
    this.form.setValue({
      email: formData.email || '',
      firstName: formData.firstName || '',
      lastName: formData.lastName || '',
      address: formData.address || '',
    });
  }

  getDossierId() {
    if (localStorage.getItem('dossierId')) {
      return localStorage.getItem('dossierId');
    } else {
      const id = Math.random().toString(36).substr(2, 9);
      localStorage.setItem('dossierId', id);
      return id;
    }
  }

  onFileOneSelect(event: any) {
    if (event.target.files.length > 0) {
      const file = event.target.files[0];
      this.fileName = file.name;
      localStorage.setItem('fileName', this.fileName);
    }
  }

  onDropOneSelect(event: DragEvent) {
    event.preventDefault();
    if (event.dataTransfer?.items) {
      const file = event.dataTransfer.items[0].getAsFile();
      this.fileName = file?.name || '';
      localStorage.setItem('fileName', this.fileName);
    }
  }

  onCniSelect(event: any) {
    this.cni = event.target.value;
    localStorage.setItem('cni', this.cni);
  }

  onDropCniSelect(event: DragEvent) {
    event.preventDefault();
    if (event.dataTransfer?.items) {
      const file = event.dataTransfer.items[0].getAsFile();
      this.cni = file?.name || '';
      localStorage.setItem('cni', this.cni);
    }
  }

  onDragOver(event: DragEvent) {
    event.stopPropagation();
    event.preventDefault();
  }

  keepLocalStorageToDate() {
    localStorage.setItem('formData', JSON.stringify(this.form.value));
  }

  onSubmit() {
    localStorage.setItem('formData', JSON.stringify(this.form.value));
  }
}
