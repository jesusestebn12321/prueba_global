import { Component } from '@angular/core';
import { EmployeeService } from '../services/employee.service';
import { ActivatedRoute, Router } from '@angular/router';
import Swal from 'sweetalert2'


@Component({
  selector: 'app-employee-form',
  templateUrl: './employee-form.component.html',
  styleUrls: ['./employee-form.component.css']
})
export class EmployeeFormComponent {
  employee = {
    first_last_name: '',
    second_last_name: '',
    first_name: '',
    other_names: '',
    country: '',
    identification_type: '',
    identification_number: '',
    hire_date: '',
    area: ''
  };

  constructor(private employeeService: EmployeeService, private router: Router) {}

  createEmployee() {
    this.employeeService.createEmployee(this.employee).subscribe(response => {
      console.log('Employee created successfully', response);
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Employee created successfully",
        showConfirmButton: false,
        timer: 1500
      });
      this.router.navigate(['/employee']);
    }, error => {
      console.error('Employee creation failed', error);
    });
  }
}
