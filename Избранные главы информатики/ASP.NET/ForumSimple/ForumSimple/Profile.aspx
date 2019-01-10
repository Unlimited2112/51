<%@ Page Title="" Language="C#" MasterPageFile="~/Template.Master" AutoEventWireup="true" CodeBehind="Profile.aspx.cs" Inherits="ForumSimple.Profile" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    <h3><asp:Literal ID="literalUserName" runat="server" Text="Логин" /> <small>профиль пользователя</small></h3>
    <asp:Panel ID="rulesPanel" runat="server" CssClass="panel panel-default" 
        onprerender="rulesPanel_PreRender">
        <div class="panel-heading">Изменение роли</div>
        <div class="panel-body">
            <asp:RadioButtonList ID="rulesRadioButtonList" runat="server" ValidationGroup="RulesGroup">
            </asp:RadioButtonList>
        </div>
        <div class="panel-footer">
            <asp:Button runat="server" ID="BtnRuleSave" Text="Изменить роль" onclick="BtnRuleSave_Click" CssClass="btn btn-default" ValidationGroup="RulesGroup" />
            <asp:Literal ID="LiteralRulesResult" runat="server" />
        </div>
    </asp:Panel>
    <asp:Panel ID="passwordPanel" runat="server" CssClass="panel panel-default">
        <div class="panel-heading">Изменение пароля</div>
        <div class="panel-body">
            <div class="form-group">
                <asp:Label ID="LabelPasswordOld" runat="server" AssociatedControlID="PasswordOld" CssClass="control-label">Старый пароль</asp:Label>
                <asp:TextBox runat="server" ID="PasswordOld" TextMode="Password" CssClass="form-control" ValidationGroup="PasswordGroup" />
                <span class="help-block">
                    <asp:RequiredFieldValidator runat="server" ID="reqPasswordOld" ControlToValidate="PasswordOld" Display="Dynamic" ValidationGroup="PasswordGroup">Необходимо ввести старый пароль!</asp:RequiredFieldValidator>
                    <asp:CustomValidator runat="server" ID="cstmPasswordOld" ControlToValidate="PasswordOld" onServerValidate="cstmPasswordOld_ServerValidate" Display="Dynamic" EnableClientScript="False" ValidationGroup="PasswordGroup">Неверный старый пароль!</asp:CustomValidator>
                </span>
            </div>
            <div class="form-group">
                <asp:Label ID="LabelPassword" runat="server" AssociatedControlID="Password" CssClass="control-label">Новый пароль</asp:Label>
                <asp:TextBox runat="server" ID="Password" TextMode="Password" CssClass="form-control" ValidationGroup="PasswordGroup" />
                <span class="help-block">
                    <asp:RequiredFieldValidator runat="server" ID="reqPassword" ControlToValidate="Password" Display="Dynamic" ValidationGroup="PasswordGroup">Пароль не может быть пустым!</asp:RequiredFieldValidator>
                </span>
            </div>
            <div class="form-group">
                <asp:Label ID="LabelPasswordConfirm" runat="server" AssociatedControlID="PasswordConfirm" CssClass="control-label">Подтверждение пароля</asp:Label>
                <asp:TextBox runat="server" ID="PasswordConfirm" TextMode="Password" CssClass="form-control" ValidationGroup="PasswordGroup" />
                <span class="help-block">
                    <asp:RequiredFieldValidator runat="server" ID="reqPasswrodConfirm" ControlToValidate="PasswordConfirm" Display="Dynamic" ValidationGroup="PasswordGroup">Необходимо ввести подтверждение пароля!</asp:RequiredFieldValidator>
                    <asp:CompareValidator runat="server" ID="comparePassword" ControlToCompare="Password" ControlToValidate="PasswordConfirm" Display="Dynamic" ValidationGroup="PasswordGroup">Пароли должны совпадать!</asp:CompareValidator>
                </span>
            </div>
        </div>
        <div class="panel-footer">
            <asp:Button runat="server" ID="BtnPasswordSave" Text="Изменить пароль" onclick="BtnPasswordSave_Click" CssClass="btn btn-default" ValidationGroup="PasswordGroup" />
            <asp:Literal ID="LiteralPasswordResult" runat="server" />
        </div>
    </asp:Panel>
</asp:Content>
