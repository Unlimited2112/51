namespace WindowsFormsApplication
{
    partial class MainForm
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
            this.dgvCashflows = new System.Windows.Forms.DataGridView();
            this.directionTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.mainMenuStrip = new System.Windows.Forms.MenuStrip();
            this.mmNew = new System.Windows.Forms.ToolStripMenuItem();
            this.mmEdit = new System.Windows.Forms.ToolStripMenuItem();
            this.mmDelete = new System.Windows.Forms.ToolStripMenuItem();
            this.idTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.descriptionTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.summaTextBoxColumn = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.cashflowBindingSource = new System.Windows.Forms.BindingSource(this.components);
            ((System.ComponentModel.ISupportInitialize)(this.dgvCashflows)).BeginInit();
            this.mainMenuStrip.SuspendLayout();
            ((System.ComponentModel.ISupportInitialize)(this.cashflowBindingSource)).BeginInit();
            this.SuspendLayout();
            // 
            // dgvCashflows
            // 
            this.dgvCashflows.AllowUserToAddRows = false;
            this.dgvCashflows.AllowUserToDeleteRows = false;
            this.dgvCashflows.AutoGenerateColumns = false;
            this.dgvCashflows.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.dgvCashflows.Columns.AddRange(new System.Windows.Forms.DataGridViewColumn[] {
            this.idTextBoxColumn,
            this.descriptionTextBoxColumn,
            this.summaTextBoxColumn,
            this.directionTextBoxColumn});
            this.dgvCashflows.DataSource = this.cashflowBindingSource;
            this.dgvCashflows.Dock = System.Windows.Forms.DockStyle.Fill;
            this.dgvCashflows.Location = new System.Drawing.Point(0, 24);
            this.dgvCashflows.Name = "dgvCashflows";
            this.dgvCashflows.ReadOnly = true;
            this.dgvCashflows.Size = new System.Drawing.Size(584, 337);
            this.dgvCashflows.TabIndex = 0;
            // 
            // directionTextBoxColumn
            // 
            this.directionTextBoxColumn.DataPropertyName = "Direction";
            this.directionTextBoxColumn.HeaderText = "Направление";
            this.directionTextBoxColumn.Name = "directionTextBoxColumn";
            this.directionTextBoxColumn.ReadOnly = true;
            // 
            // mainMenuStrip
            // 
            this.mainMenuStrip.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.mmNew,
            this.mmEdit,
            this.mmDelete});
            this.mainMenuStrip.Location = new System.Drawing.Point(0, 0);
            this.mainMenuStrip.Name = "mainMenuStrip";
            this.mainMenuStrip.Size = new System.Drawing.Size(584, 24);
            this.mainMenuStrip.TabIndex = 1;
            this.mainMenuStrip.Text = "Главное меню";
            // 
            // mmNew
            // 
            this.mmNew.Name = "mmNew";
            this.mmNew.Size = new System.Drawing.Size(71, 20);
            this.mmNew.Text = "Добавить";
            this.mmNew.Click += new System.EventHandler(this.mmNew_Click);
            // 
            // mmEdit
            // 
            this.mmEdit.Name = "mmEdit";
            this.mmEdit.Size = new System.Drawing.Size(73, 20);
            this.mmEdit.Text = "Изменить";
            this.mmEdit.Click += new System.EventHandler(this.mmEdit_Click);
            // 
            // mmDelete
            // 
            this.mmDelete.Name = "mmDelete";
            this.mmDelete.Size = new System.Drawing.Size(63, 20);
            this.mmDelete.Text = "Удалить";
            this.mmDelete.Click += new System.EventHandler(this.mmDelete_Click);
            // 
            // idTextBoxColumn
            // 
            this.idTextBoxColumn.DataPropertyName = "Id";
            this.idTextBoxColumn.HeaderText = "Код";
            this.idTextBoxColumn.Name = "idTextBoxColumn";
            this.idTextBoxColumn.ReadOnly = true;
            this.idTextBoxColumn.Width = 50;
            // 
            // descriptionTextBoxColumn
            // 
            this.descriptionTextBoxColumn.DataPropertyName = "Description";
            this.descriptionTextBoxColumn.HeaderText = "Описание";
            this.descriptionTextBoxColumn.Name = "descriptionTextBoxColumn";
            this.descriptionTextBoxColumn.ReadOnly = true;
            this.descriptionTextBoxColumn.Width = 250;
            // 
            // summaTextBoxColumn
            // 
            this.summaTextBoxColumn.DataPropertyName = "Summa";
            this.summaTextBoxColumn.HeaderText = "Сумма";
            this.summaTextBoxColumn.Name = "summaTextBoxColumn";
            this.summaTextBoxColumn.ReadOnly = true;
            // 
            // cashflowBindingSource
            // 
            this.cashflowBindingSource.DataSource = typeof(WindowsFormsApplication.Model.Cashflow);
            // 
            // MainForm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(584, 361);
            this.Controls.Add(this.dgvCashflows);
            this.Controls.Add(this.mainMenuStrip);
            this.MainMenuStrip = this.mainMenuStrip;
            this.Name = "MainForm";
            this.Text = "Движения денежных средств";
            ((System.ComponentModel.ISupportInitialize)(this.dgvCashflows)).EndInit();
            this.mainMenuStrip.ResumeLayout(false);
            this.mainMenuStrip.PerformLayout();
            ((System.ComponentModel.ISupportInitialize)(this.cashflowBindingSource)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.DataGridView dgvCashflows;
        private System.Windows.Forms.BindingSource cashflowBindingSource;
        private System.Windows.Forms.DataGridViewTextBoxColumn idTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn descriptionTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn summaTextBoxColumn;
        private System.Windows.Forms.DataGridViewTextBoxColumn directionTextBoxColumn;
        private System.Windows.Forms.MenuStrip mainMenuStrip;
        private System.Windows.Forms.ToolStripMenuItem mmNew;
        private System.Windows.Forms.ToolStripMenuItem mmEdit;
        private System.Windows.Forms.ToolStripMenuItem mmDelete;
    }
}