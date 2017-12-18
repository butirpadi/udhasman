<div class="row" >
	<table class="table table-bordered table-condensed" id="table-kalkulasi" >
                    <tbody>
                        <tr>
                            <td><label>No Nota</label></td>
                            <td>
                                <input type="text" autocomplete="off" name="no_nota_timbang" class="form-control" value="CUST/" >
                            </td>
                        </tr>
                        <tr>
                            <td><label>Kalkulasi</label></td>
                            <td>
                                <select name="kalkulasi" class="form-control" >
                                    <option value="R" >Ritase</option>
                                    <option value="K" >Kubikasi</option>
                                    <option value="T" >Tonase</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td>
                                <label>Panjang</label>
                                </td>
                            <td>
                                <input type="text" name="panjang" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td><label>Lebar</label></td>
                            <td>
                                <input type="text" name="lebar" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td><label>Tinggi</label></td>
                            <td>
                                <input type="text" name="tinggi" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-kubikasi" >
                            <td><label>Volume</label></td>
                            <td>
                                <input type="text" name="volume" class="form-control text-right " disabled>
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td><label>Gross</label></td>
                            <td>
                                <input type="text" name="gross" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td><label>Tarre</label></td>
                            <td>
                                <input type="text" name="tarre" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-tonase" >
                            <td><label>Netto</label></td>
                            <td>
                                <input type="text" name="netto" class="form-control text-right" disabled>
                            </td>
                        </tr>
                        <tr class="row-price" >
                            <td>
                                <label>Unit Price</label>
                            </td>
                            <td>
                                <input type="text" name="unit_price" class="form-control text-right">
                            </td>
                        </tr>
                        <tr class="row-price" >
                            <td>
                                <label>Total</label>
                            </td>
                            <td>
                                <input type="text" name="total" class="form-control text-right" disabled>
                            </td>
                        </tr>

                    </tbody>
                </table>
</div>