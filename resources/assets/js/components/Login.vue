<template>	
	<div class="padding-top">	
		<div class="card text-center border-primary mx-auto col-5">
			<div class="card-header">
				<ul class="nav nav-tabs card-header-tabs nav-justified" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#login" role="tab">Login</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#register" role="tab">Register</a>
					</li>
				</ul>
			</div>
			<div class="card-body">
				<div class="tab-content">
					<div class="tab-pane active" id="login" role="tabpanel">
						<form>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="username">Username</label>
								<div class="col-8">
									<input class="form-control" type="text" name="username">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="password">Password</label>
								<div class="col-8">
									<input class="form-control" type="password" name="password">
								</div>
							</div>
							<a type="submit" class="btn btn-primary" href="#">Login</a>
						</form>
					</div>
					<div class="tab-pane col-12" id="register" role="tabpanel">
						<form>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="name">Name</label>
								<div class="col-8">
									<input class="form-control" type="text" name="name">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="email">Email</label>
								<div class="col-8">
									<input class="form-control" type="text" name="email">
								</div>

							</div>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="username">Username</label>
								<div class="col-8">
									<input class="form-control" type="text" name="username">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="password">Password</label>
								<div class="col-8">
									<input class="form-control" type="password" name="password">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-4 col-form-label" for="password_confirm">Confirm Password</label>
								<div class="col-8">
									<input class="form-control" type="password" name="password_confirm">
								</div>
							</div>
							<a type="submit" class="btn btn-primary" href="#">Create Account</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>

</script>
<style>
.col-5 {
	padding: 0;
}

.padding-top {
	padding-top: 55px;
}

</style>