namespace Hotel.Forms
{
    partial class RoomForm
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            this.btnCancel = new System.Windows.Forms.Button();
            this.btnOk = new System.Windows.Forms.Button();
            this.label2 = new System.Windows.Forms.Label();
            this.bindingItem = new System.Windows.Forms.BindingSource(this.components);
            this.hotelDataSet = new Hotel.HotelDataSet();
            this.fldFloor = new System.Windows.Forms.NumericUpDown();
            this.fldNumbers = new System.Windows.Forms.NumericUpDown();
            this.fldType = new System.Windows.Forms.ComboBox();
            this.typesBindingSource = new System.Windows.Forms.BindingSource(this.components);
            this.fldPersons = new System.Windows.Forms.NumericUpDown();
            this.label1 = new System.Windows.Forms.Label();
            this.label3 = new System.Windows.Forms.Label();
            this.label4 = new System.Windows.Forms.Label();
            ((System.ComponentModel.ISupportInitialize)(this.bindingItem)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.hotelDataSet)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.fldFloor)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.fldNumbers)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.typesBindingSource)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.fldPersons)).BeginInit();
            this.SuspendLayout();
            // 
            // btnCancel
            // 
            this.btnCancel.Location = new System.Drawing.Point(197, 117);
            this.btnCancel.Name = "btnCancel";
            this.btnCancel.Size = new System.Drawing.Size(75, 23);
            this.btnCancel.TabIndex = 11;
            this.btnCancel.Text = "Отмена";
            this.btnCancel.UseVisualStyleBackColor = true;
            this.btnCancel.Click += new System.EventHandler(this.btnCancel_Click);
            // 
            // btnOk
            // 
            this.btnOk.Location = new System.Drawing.Point(116, 117);
            this.btnOk.Name = "btnOk";
            this.btnOk.Size = new System.Drawing.Size(75, 23);
            this.btnOk.TabIndex = 10;
            this.btnOk.Text = "Ok";
            this.btnOk.UseVisualStyleBackColor = true;
            this.btnOk.Click += new System.EventHandler(this.btnOk_Click);
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(12, 14);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(33, 13);
            this.label2.TabIndex = 9;
            this.label2.Text = "Этаж";
            // 
            // bindingItem
            // 
            this.bindingItem.DataMember = "RoomsView";
            this.bindingItem.DataSource = this.hotelDataSet;
            // 
            // hotelDataSet
            // 
            this.hotelDataSet.DataSetName = "HotelDataSet";
            this.hotelDataSet.SchemaSerializationMode = System.Data.SchemaSerializationMode.IncludeSchema;
            // 
            // fldFloor
            // 
            this.fldFloor.DataBindings.Add(new System.Windows.Forms.Binding("Value", this.bindingItem, "Floor", true));
            this.fldFloor.Location = new System.Drawing.Point(68, 12);
            this.fldFloor.Maximum = new decimal(new int[] {
            9,
            0,
            0,
            0});
            this.fldFloor.Minimum = new decimal(new int[] {
            1,
            0,
            0,
            0});
            this.fldFloor.Name = "fldFloor";
            this.fldFloor.Size = new System.Drawing.Size(204, 20);
            this.fldFloor.TabIndex = 12;
            this.fldFloor.Value = new decimal(new int[] {
            1,
            0,
            0,
            0});
            // 
            // fldNumbers
            // 
            this.fldNumbers.DataBindings.Add(new System.Windows.Forms.Binding("Value", this.bindingItem, "Number", true));
            this.fldNumbers.Location = new System.Drawing.Point(68, 38);
            this.fldNumbers.Maximum = new decimal(new int[] {
            99,
            0,
            0,
            0});
            this.fldNumbers.Minimum = new decimal(new int[] {
            1,
            0,
            0,
            0});
            this.fldNumbers.Name = "fldNumbers";
            this.fldNumbers.Size = new System.Drawing.Size(204, 20);
            this.fldNumbers.TabIndex = 13;
            this.fldNumbers.Value = new decimal(new int[] {
            1,
            0,
            0,
            0});
            // 
            // fldType
            // 
            this.fldType.DataBindings.Add(new System.Windows.Forms.Binding("SelectedValue", this.bindingItem, "TypeId", true));
            this.fldType.DataSource = this.typesBindingSource;
            this.fldType.DisplayMember = "Code";
            this.fldType.FormattingEnabled = true;
            this.fldType.Location = new System.Drawing.Point(68, 64);
            this.fldType.Name = "fldType";
            this.fldType.Size = new System.Drawing.Size(204, 21);
            this.fldType.TabIndex = 14;
            this.fldType.ValueMember = "Id";
            // 
            // typesBindingSource
            // 
            this.typesBindingSource.DataMember = "Types";
            this.typesBindingSource.DataSource = this.hotelDataSet;
            // 
            // fldPersons
            // 
            this.fldPersons.DataBindings.Add(new System.Windows.Forms.Binding("Value", this.bindingItem, "PersonsMax", true));
            this.fldPersons.Location = new System.Drawing.Point(68, 91);
            this.fldPersons.Maximum = new decimal(new int[] {
            6,
            0,
            0,
            0});
            this.fldPersons.Minimum = new decimal(new int[] {
            1,
            0,
            0,
            0});
            this.fldPersons.Name = "fldPersons";
            this.fldPersons.Size = new System.Drawing.Size(204, 20);
            this.fldPersons.TabIndex = 15;
            this.fldPersons.Value = new decimal(new int[] {
            1,
            0,
            0,
            0});
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(12, 40);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(41, 13);
            this.label1.TabIndex = 16;
            this.label1.Text = "Номер";
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(12, 67);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(26, 13);
            this.label3.TabIndex = 17;
            this.label3.Text = "Тип";
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(12, 93);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(50, 13);
            this.label4.TabIndex = 18;
            this.label4.Text = "Max чел.";
            // 
            // RoomForm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(284, 151);
            this.Controls.Add(this.label4);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.fldPersons);
            this.Controls.Add(this.fldType);
            this.Controls.Add(this.fldNumbers);
            this.Controls.Add(this.fldFloor);
            this.Controls.Add(this.btnCancel);
            this.Controls.Add(this.btnOk);
            this.Controls.Add(this.label2);
            this.Name = "RoomForm";
            this.Text = "Гостиничный номер";
            this.Load += new System.EventHandler(this.RoomForm_Load);
            ((System.ComponentModel.ISupportInitialize)(this.bindingItem)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.hotelDataSet)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.fldFloor)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.fldNumbers)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.typesBindingSource)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.fldPersons)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Button btnCancel;
        private System.Windows.Forms.Button btnOk;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.BindingSource bindingItem;
        private HotelDataSet hotelDataSet;
        private System.Windows.Forms.NumericUpDown fldFloor;
        private System.Windows.Forms.NumericUpDown fldNumbers;
        private System.Windows.Forms.ComboBox fldType;
        private System.Windows.Forms.BindingSource typesBindingSource;
        private System.Windows.Forms.NumericUpDown fldPersons;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label4;
    }
}