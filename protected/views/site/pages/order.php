<style>
	.cont-from-to{
		display: inline-block;
		margin-right: 40px;
	}
</style>

<h4 class="ui-box-header ui-corner-all">
	Create Order
</h4>

<div class="form">
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	
	<div class="row" style="display: block">
		<label class="required">
			Customer
			<span class="required">*</span>
		</label>
		<input size="40"/>
	</div>
	
	<div class="cont-from-to">
				<h1>Collection Location</h1>

		<div class="row">
			<label class="required">
				Recent
				<span class="required">*</span>
			</label>
			<input size="40"/>
		</div>

		<div class="row">
			<label class="required">
				Directory
				<span class="required">*</span>
			</label>
			<input size="40"/>
		</div>

		<h1>From</h1>

		<div class="row">
			<label class="required">
				Zone
				<span class="required">*</span>
			</label>
			<input size="20"/>
		</div>

		<div class="row">
			<label class="required">
				Arival
				<span class="required">*</span>
			</label>
			<input size="20"/>
		</div>

		<div class="row">
			<label class="required">
				To
				<span class="required">*</span>
			</label>
			<input size="20"/>
		</div>
</div>
	
	<div class="cont-from-to">
		<h1>Dellivery Location</h1>

		<div class="row">
			<label class="required">
				Recent
				<span class="required">*</span>
			</label>
			<input size="40"/>
		</div>

		<div class="row">
			<label class="required">
				Directory
				<span class="required">*</span>
			</label>
			<input size="40"/>
		</div>
	
		<h1>To</h1>

		<div class="row">
			<label class="required">
				Zone
				<span class="required">*</span>
			</label>
			<input size="20"/>
		</div>

		<div class="row">
			<label class="required">
				Arival
				<span class="required">*</span>
			</label>
			<input size="20"/>
		</div>

		<div class="row">
			<label class="required">
				To
				<span class="required">*</span>
			</label>
			<input size="20"/>
		</div>
	</div>	
	
	<div class="row">
		<label class="required">
			Description
			<span class="required">*</span>
		</label>
		<textarea cols="90" rows="5"></textarea>
	</div>
	
	<div class="row">
			<label class="required">
				Quantity
				<span class="required">*</span>
			</label>
			<input size="20"/>
		</div>

		<div class="row">
			<label class="required">
				Weight
				<span class="required">*</span>
			</label>
			<input size="20"/>
		</div>

		<div class="row">
			<label class="required">
				Distance
				<span class="required">*</span>
			</label>
			<input size="20"/>
		</div>
	
	<div class="row">
			<label class="required">
				Dimensions
				<span class="required">*</span>
			</label>
			<input size="5"/> * <input size="5"/> * <input size="5"/>
		</div>
	
	<div class="row">
			<label class="required">
				Reference#
				<span class="required">*</span>
			</label>
			<input size="40"/>
		</div>

		<div class="row">
			<label class="required">
				Purchase Order#
				<span class="required">*</span>
			</label>
			<input size="40"/>
		</div>
	
	<div class="row">
			<label class="required">
				Vehicle Type
				<span class="required">*</span>
			</label>
			<input size="40"/>
		</div>

		<div class="row">
			<label class="required">
				Assigned To
				<span class="required">*</span>
			</label>
			<input size="40"/>
		</div>
	
	<div class="row">
			<label class="required">
				Cubic Dimension
				<span class="required">*</span>
			</label>
			<input size="5"/>
		</div>
	
	<div class="row">
			<label class="required">
			Service Level
				<span class="required">*</span>
			</label>
			<input size="40"/>
		</div>

	
	<div class="row">
			<label class="required">
			Base Price
				<span class="required">*</span>
			</label>
		<div class="zone">
				<input type="radio" />
				<label style="display: inline">Zone Based</label>
				<span>0.00</span>
				<br />
				<input type="radio" />
				<label style="display: inline">Distance Based</label>
				<span>0.00</span>
				<br />
				<input type="radio" />
				<label style="display: inline">Flate Rated</label>
				<span>0.00</span>
			</div>
	</div>
	
	<div class="row">
			<label class="required">
				Option Total
				<span class="required">*</span>
			</label>
			<input size="10"/>
		</div>
	
	<div class="row">
			<label class="required">
				Misc. Adjustment
				<span class="required">*</span>
			</label>
			<input size="10"/>
		</div>
	
	<div class="row">
		<label class="required">
				Total Cost
			</label>
		<h1>Not Available</h1>
	</div>
	
	<div class="row buttons">
		<input type="submit" value="save" />
	</div>
		
</div>
