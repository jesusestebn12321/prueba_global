import { Component, Input, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Employee } from '../../models/employee.model';
import { EmployeeService } from '../../services/employee.service';
import Swal from 'sweetalert2'



@Component({
  selector: 'app-edit',
  templateUrl: './edit.component.html',
  styleUrl: './edit.component.css'
})
export class EditComponent implements OnInit{

  @Input() viewMode = false;

  @Input() currentEmployee: Employee = {
      email: '',
      description: "",
      published: false,
      first_last_name: "",
      second_last_name: "",
      first_name: "",
      other_names: "",
      country: "",
      identification_type: "",
      identification_number: "",
      hire_date: "",
      area: ""
  };

  message = '';

  constructor(
    private EmployeeService: EmployeeService,
    private route: ActivatedRoute,
    private router: Router) { }

    ngOnInit(): void {
      if (!this.viewMode) {
        this.message = '';
        this.getEmploye(this.route.snapshot.params["id"]);
      }
    }
  
    getEmploye(id: number): void {
      this.EmployeeService.getEmployee(id)
        .subscribe({
          next: (data) => {
            this.currentEmployee = data;
            console.log(data);
          },
          error: (e) => console.error(e)
        });
    }

    updateEmployee(): void {
      this.message = '';
      var id = this.currentEmployee.id;
      this.EmployeeService.updateEmployee(id, this.currentEmployee)
        .subscribe({
          next: (res) => {
            console.log(res);
            this.message = res.message ? res.message : 'This Employee updated successfully!';
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: this.message,
              showConfirmButton: false,
              timer: 1500
            });  
            this.router.navigate(['/employee']);
          },
          error: (e) => console.error(e)
        });
    }


}
