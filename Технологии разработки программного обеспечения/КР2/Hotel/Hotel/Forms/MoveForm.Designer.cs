namespace Hotel.Forms
{
    partial class MoveForm
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
            this.fldDates = new System.Windows.Forms.DateTimePicker();
            this.fldAction = new System.Windows.Forms.ComboBox();
            this.actionsBindingSource = new System.Windows.Forms.BindingSource(this.components);
            this.fldRoom = new System.Windows.Forms.ComboBox();
            this.roomsViewBindingSource = new System.Windows.Forms.BindingSource(this.components);
            this.fldDatesIn = new System.Windows.Forms.DateTimePicker();
            this.fldDays = new System.Windows.Forms.NumericUpDown();
            this.fldPersons = new System.Windows.Forms.NumericUpDown();
            this.lblUserName = new System.Windows.Forms.Label();
            this.label1 = new System.Windows.Forms.Label();
            this.label3 = new System.Windows.Forms.Label();
            this.label4 = new System.Windows.Forms.Label();
            this.label5 = new System.Windows.Forms.Label();
            this.label6 = new System.Windows.Forms.Label();
            ((System.ComponentModel.ISupportInitialize)(this.bindingItem)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.hotelDataSet)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.actionsBindingSource)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.roomsViewBindingSource)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.fldDays)).BeginInit();
            ((System.ComponentModel.ISupportInitialize)(this.fldPersons)).BeginInit();
            this.SuspendLayout();
            // 
            // btnCancel
            // 
            this.btnCancel.Location = new System.Drawing.Point(247, 226);
            this.btnCancel.Name = "btnCancel";
            this.btnCancel.Size = new System.Drawing.Size(75, 23);
            this.btnCancel.TabIndex = 11;
            this.btnCancel.Text = "Отмена";
            this.btnCancel.UseVisualStyleBackColor = true;
            this.btnCancel.Click += new System.EventHandler(this.btnCancel_Click);
            // 
            // btnOk
            // 
            this.btnOk.Location = new System.Drawing.Point(166, 226);
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
            this.label2.Location = new System.Drawing.Point(12, 15);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(86, 13);
            this.label2.TabIndex = 9;
            this.label2.Text = "Дата движения";
            // 
            // bindingItem
            // 
            this.bindingItem.DataMember = "MovesView";
            this.bindingItem.DataSource = this.hotelDataSet;
            // 
            // hotelDataSet
            // 
            this.hotelDataSet.DataSetName = "HotelDataSet";
            this.hotelDataSet.SchemaSerializationMode = System.Data.SchemaSerializationMode.IncludeSchema;
            // 
            // fldDates
            // 
            this.fldDates.DataBindings.Add(new System.Windows.Forms.Binding("Value", this.bindingItem, "Dates", true));
            this.fldDates.Location = new System.Drawing.Point(122, 12);
            this.fldDates.Name = "fldDates";
            this.fldDates.Size = new System.Drawing.Size(200, 20);
            this.fldDates.TabIndex = 12;
            // 
            // fldAction
            // 
            this.fldAction.DataBindings.Add(new System.Windows.Forms.Binding("SelectedValue", this.bindingItem, "ActionId", true));
            this.fldAction.DataSource = this.actionsBindingSource;
            this.fldAction.DisplayMember = "Name";
            this.fldAction.FormattingEnabled = true;
            this.fldAction.Location = new System.Drawing.Point(122, 38);
            this.fldAction.Name = "fldAction";
            this.fldAction.Size = new System.Drawing.Size(200, 21);
            this.fldAction.TabIndex = 13;
            this.fldAction.ValueMember = "Id";
            // 
            // actionsBindingSource
            // 
            this.actionsBindingSource.DataMember = "Actions";
            this.actionsBindingSource.DataSource = this.hotelDataSet;
            // 
            // fldRoom
            // 
            this.fldRoom.DataBindings.Add(new System.Windows.Forms.Binding("SelectedValue", this.bindingItem, "RoomId", true));
            this.fldRoom.DataSource = this.roomsViewBindingSource;
            this.fldRoom.DisplayMember = "Id";
            this.fldRoom.FormattingEnabled = true;
            this.fldRoom.Location = new System.Drawing.Point(122, 65);
            this.fldRoom.Name = "fldRoom";
            this.fldRoom.Size = new System.Drawing.Size(200, 21);
            this.fldRoom.TabIndex = 14;
            this.fldRoom.ValueMember = "Id";
            // 
            // roomsViewBindingSource
            // 
            this.roomsViewBindingSource.DataMember = "RoomsView";
            this.roomsViewBindingSource.DataSource = this.hotelDataSet;
            // 
            // fldDatesIn
            // 
            this.fldDatesIn.DataBindings.Add(new System.Windows.Forms.Binding("Value", this.bindingItem, "DatesIn", true));
            this.fldDatesIn.Location = new System.Drawing.Point(122, 92);
            this.fldDatesIn.Name = "fldDatesIn";
            this.fldDatesIn.Size = new System.Drawing.Size(200, 20);
            this.fldDatesIn.TabIndex = 15;
            // 
            // fldDays
            // 
            this.fldDays.DataBindings.Add(new System.Windows.Forms.Binding("Value", this.bindingItem, "DaysCnt", true));
            this.fldDays.Location = new System.Drawing.Point(122, 118);
            this.fldDays.Name = "fldDays";
            this.fldDays.Size = new System.Drawing.Size(200, 20);
            this.fldDays.TabIndex = 16;
            // 
            // fldPersons
            // 
            this.fldPersons.DataBindings.Add(new System.Windows.Forms.Binding("Value", this.bindingItem, "PersonsCnt", true));
            this.fldPersons.Location = new System.Drawing.Point(122, 144);
            this.fldPersons.Name = "fldPersons";
            this.fldPersons.Size = new System.Drawing.Size(200, 20);
            this.fldPersons.TabIndex = 17;
            // 
            // lblUserName
            // 
            this.lblUserName.AutoSize = true;
            this.lblUserName.DataBindings.Add(new System.Windows.Forms.Binding("Text", this.bindingItem, "UserName", true));
            this.lblUserName.Location = new System.Drawing.Point(122, 171);
            this.lblUserName.Name = "lblUserName";
            this.lblUserName.Size = new System.Drawing.Size(0, 13);
            this.lblUserName.TabIndex = 18;
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(12, 41);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(79, 13);
            this.label1.TabIndex = 19;
            this.label1.Text = "Вид движения";
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(12, 68);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(41, 13);
            this.label3.TabIndex = 20;
            this.label3.Text = "Номер";
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(12, 95);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(71, 13);
            this.label4.TabIndex = 21;
            this.label4.Text = "Дата начала";
            // 
            // label5
            // 
            this.label5.AutoSize = true;
            this.label5.Location = new System.Drawing.Point(12, 120);
            this.label5.Name = "label5";
            this.label5.Size = new System.Drawing.Size(68, 13);
            this.label5.TabIndex = 22;
            this.label5.Text = "Кол-во дней";
            // 
            // label6
            // 
            this.label6.AutoSize = true;
            this.label6.Location = new System.Drawing.Point(12, 146);
            this.label6.Name = "label6";
            this.label6.Size = new System.Drawing.Size(85, 13);
            this.label6.TabIndex = 23;
            this.label6.Text = "Кол-во человек";
            // 
            // MoveForm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(334, 261);
            this.Controls.Add(this.label6);
            this.Controls.Add(this.label5);
            this.Controls.Add(this.label4);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.lblUserName);
            this.Controls.Add(this.fldPersons);
            this.Controls.Add(this.fldDays);
            this.Controls.Add(this.fldDatesIn);
            this.Controls.Add(this.fldRoom);
            this.Controls.Add(this.fldAction);
            this.Controls.Add(this.fldDates);
            this.Controls.Add(this.btnCancel);
            this.Controls.Add(this.btnOk);
            this.Controls.Add(this.label2);
            this.Name = "MoveForm";
            this.Text = "Движение постояльцев";
            this.Load += new System.EventHandler(this.MoveForm_Load);
            ((System.ComponentModel.ISupportInitialize)(this.bindingItem)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.hotelDataSet)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.actionsBindingSource)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.roomsViewBindingSource)).EndInit();
            ((System.ComponentModel.ISupportInitialize)(this.fldDays)).EndInit();
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
        private System.Windows.Forms.DateTimePicker fldDates;
        private System.Windows.Forms.ComboBox fldAction;
        private System.Windows.Forms.ComboBox fldRoom;
        private System.Windows.Forms.DateTimePicker fldDatesIn;
        private System.Windows.Forms.NumericUpDown fldDays;
        private System.Windows.Forms.NumericUpDown fldPersons;
        private System.Windows.Forms.Label lblUserName;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.Label label5;
        private System.Windows.Forms.Label label6;
        private System.Windows.Forms.BindingSource actionsBindingSource;
        private System.Windows.Forms.BindingSource roomsViewBindingSource;
    }
}