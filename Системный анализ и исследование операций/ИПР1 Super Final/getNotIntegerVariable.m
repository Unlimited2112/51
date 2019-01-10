function getNotIntegerVariable = getNotIntegerVariable(x)
    l = length(x);
    getNotIntegerVariable = 0;
    for i = 1:l
        if ~isInteger(x(i))
            getNotIntegerVariable = struct('index', i, 'value', x(i));
            break;
        end;
    end;
end