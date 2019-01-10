namespace WindowsFormsApplication
{
    partial class CashflowsForm
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
            this.fldDescription = new System.Windows.Forms.TextBox();
            this.fldSumma = new System.Windows.Forms.NumericUpDown();
            this.fldDirection = new System.Windows.Forms.ComboBox();
            this.buttonOk = new System.Windows.Forms.Button();
            this.buttonCancel = new System.Windows.Forms.Button();
            this.label1 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.label3 = new System.Windows.Forms.Label();
            ((System.ComponentModel.ISupportInitialize)(this.fldSumma)).BeginInit();
            this.SuspendLayout();
            // 
            // fldDescription
            // 
            this.fldDescription.Location = new System.Drawing.Point(93, 12);
            this.fldDescription.Name = "fldDescription";
            this.fldDescription.Size = new System.Drawing.Size(179, 20);
            this.fldDescription.TabIndex = 0;
            // 
            // fldSumma
            // 
            this.fldSumma.DecimalPlaces = 2;
            this.fldSumma.Location = new System.Drawing.Point(93, 39);
            this.fldSumma.Maximum = new decimal(new int[] {
            9999999,
            0,
            0,
            131072});
            this.fldSumma.Minimum = new decimal(new int[] {
            1,
            0,
            0,
            131072});
            this.fldSumma.Name = "fldSumma";
            this.fldSumma.Size = new System.Drawing.Size(179, 20);
            this.fldSumma.TabIndex = 1;
            this.fldSumma.Value = new decimal(new int[] {
            1,
            0,
            0,
            131072});
            // 
            // fldDirection
            // 
            this.fldDirection.FormattingEnabled = true;
            this.fldDirection.Location = new System.Drawing.Point(93, 66);
            this.fldDirection.Name = "fldDirection";
            this.fldDirection.Size = new System.Drawing.Size(179, 21);
            this.fldDirection.TabIndex = 2;
            // 
            // buttonOk
            // 
            this.buttonOk.DialogResult = System.Windows.Forms.DialogResult.OK;
            this.buttonOk.Location = new System.Drawing.Point(93, 94);
            this.buttonOk.Name = "buttonOk";
            this.buttonOk.Size = new System.Drawing.Size(75, 23);
            this.buttonOk.TabIndex = 3;
            this.buttonOk.Text = "Ok";
            this.buttonOk.UseVisualStyleBackColor = true;
            // 
            // buttonCancel
            // 
            this.buttonCancel.DialogResult = System.Windows.Forms.DialogResult.Cancel;
            this.buttonCancel.Location = new System.Drawing.Point(197, 94);
            this.buttonCancel.Name = "buttonCancel";
            this.buttonCancel.Size = new System.Drawing.Size(75, 23);
            this.buttonCancel.TabIndex = 4;
            this.buttonCancel.Text = "Отмена";
            this.buttonCancel.UseVisualStyleBackColor = true;
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(12, 15);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(57, 13);
            this.label1.TabIndex = 5;
            this.label1.Text = "Описание";
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(12, 41);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(41, 13);
            this.label2.TabIndex = 6;
            this.label2.Text = "Сумма";
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(12, 69);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(75, 13);
            this.label3.TabIndex = 7;
            this.label3.Text = "Направление";
            // 
            // CashflowsForm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(284, 126);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.buttonCancel);
            this.Controls.Add(this.buttonOk);
            this.Controls.Add(this.fldDirection);
            this.Controls.Add(this.fldSumma);
            this.Controls.Add(this.fldDescription);
            this.Name = "CashflowsForm";
            this.Text = "ДДС";
            ((System.ComponentModel.ISupportInitialize)(this.fldSumma)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion
        private System.Windows.Forms.Button buttonOk;
        private System.Windows.Forms.Button buttonCancel;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Label label3;
        protected internal System.Windows.Forms.TextBox fldDescription;
        protected internal System.Windows.Forms.NumericUpDown fldSumma;
        protected internal System.Windows.Forms.ComboBox fldDirection;
    }
}